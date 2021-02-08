<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\SchemaExtension;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\graphql\GraphQL\Response\ResponseInterface;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;
use Drupal\mollo_blog_graphql\Plugin\GraphQL\Response\MolloBlogResponse;
use Drupal\mollo_blog_graphql\Plugin\GraphQL\Wrappers\QueryConnectionBlog;
use Exception;

/**
 * @SchemaExtension(
 *   id = "mollo_blog_extension",
 *   name = "Mollo Blog  Extension",
 *   description = "Mollo Blog Extension",
 *   schema = "mollo_blog"
 * )
 */
class MolloBlogExtension extends SdlSchemaExtensionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry) {
    $builder = new ResolverBuilder();

    $registry->addFieldResolver('Query', 'mollo_blog',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['mollo_blog']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('getMolloBlog', 'items',
      $builder->callback(function (MolloBlogResponse $response) {
        return $response->getViolations();
      })
    );

    // Create mollo_blog mutation.
    $registry->addFieldResolver('Mutation', 'createMolloBlog',
      $builder->produce('create_mollo_blog')
        ->map('data', $builder->fromArgument('data'))
    );

    $registry->addFieldResolver('MolloBlogResponse', 'mollo_blog',
      $builder->callback(function (MolloBlogResponse $response) {
        return $response->mollo_blog();
      })
    );

    $registry->addFieldResolver('MolloBlogResponse', 'errors',
      $builder->callback(function (MolloBlogResponse $response) {
        return $response->getViolations();
      })
    );

    $registry->addFieldResolver('MolloBlog', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('MolloBlog', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('MolloBlog', 'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('Query', 'mollo_blog',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['mollo_blog']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('Query', 'mollo_blog',
      $builder->produce('query_mollo_blog')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('limit', $builder->fromArgument('limit'))
    );

    // ------------------------------------------------
    //      MolloBlogConnection
    // ------------------------------------------------
    $registry->addFieldResolver('MolloBlogConnection', 'total',
      $builder->callback(function (QueryConnectionBlog $connection) {
        return $connection->total();
      })
    );

    $registry->addFieldResolver('MolloBlogConnection', 'items',
      $builder->callback(function (QueryConnectionBlog $connection) {
        dpm('items');
        return $connection->items();
      })
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
    if ($response instanceof MolloBlogResponse) {
      return 'MolloBlogResponse';
    }
    throw new Exception('Invalid response type.');
  }

}
