<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\SchemaExtension;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\graphql\GraphQL\Response\ResponseInterface;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;
use Drupal\mollo_blog_graphql\Plugin\GraphQL\Response\BlogPostResponse;
use Exception;

/**
 * @SchemaExtension(
 *   id = "blog_extension",
 *   name = "BlogPost  Extension",
 *   description = "BlogPost Extension for Mutations",
 *   schema = "blog"
 * )
 */
class BlogPostExtension extends SdlSchemaExtensionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry) {
    $builder = new ResolverBuilder();

    $registry->addFieldResolver('Query', 'blog_post',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['blog_post']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('getBlogPosts', 'items',
      $builder->callback(function (BlogPostResponse $response) {
        return $response->getViolations();
      })
    );

    // Create blog_post mutation.
    $registry->addFieldResolver('Mutation', 'createBlogPost',
      $builder->produce('create_blog_post')
        ->map('data', $builder->fromArgument('data'))
    );

    $registry->addFieldResolver('BlogPostResponse', 'blog_post',
      $builder->callback(function (BlogPostResponse $response) {
        return $response->blog_post();
      })
    );

    $registry->addFieldResolver('BlogPostResponse', 'errors',
      $builder->callback(function (BlogPostResponse $response) {
        return $response->getViolations();
      })
    );

    $registry->addFieldResolver('BlogPost', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('BlogPost', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('BlogPost', 'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );


    // Response type resolver.
    $registry->addTypeResolver('Response', [
      __CLASS__,
      'resolveResponse',
    ]);
  }

  /**
   * Resolves the response type.
   *
   * @param \Drupal\graphql\GraphQL\Response\ResponseInterface $response
   *   Response object.
   *
   * @return string
   *   Response type.
   *
   * @throws \Exception
   *   Invalid response type.
   */
  public static function resolveResponse(ResponseInterface $response): string {
    // Resolve content response.
    if ($response instanceof BlogPostResponse) {
      return 'BlogPostResponse';
    }
    throw new Exception('Invalid response type.');
  }

}
