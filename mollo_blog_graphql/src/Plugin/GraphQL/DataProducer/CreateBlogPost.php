<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\DataProducer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\mollo_blog_graphql\Plugin\GraphQL\Response\BlogPostResponse;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a new blog_post entity.
 *
 * @DataProducer(
 *   id = "create_blog_post",
 *   name = @Translation("Create BlogPost"),
 *   description = @Translation("Creates a new blog_post."),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("BlogPost (Mollo)")
 *   ),
 *   consumes = {
 *     "data" = @ContextDefinition("any",
 *       label = @Translation("BlogPost data")
 *     )
 *   }
 * )
 */
class CreateBlogPost extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * CreateBlogPost constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }

  /**
   * Creates an blog_post.
   *
   * @param array $data
   *   The submitted values for the blog_post.
   *
   *   The newly created blog_post.
   *
   * @throws \Exception
   */
  public function resolve(array $data): BlogPostResponse {
    $response = new BlogPostResponse();
    if ($this->currentUser->hasPermission("create mollo_blog content")) {
      $values = [
        'type' => 'blog_post',
        'title' => $data['title'],
        'body' => $data['description'],
      ];
      $node = Node::create($values);
      $node->save();
      $response->setBlogPost($node);
    }
    else {
      $response->addViolation(
        $this->t('You do not have permissions to create blog posts.')
      );
    }
    return $response;
  }

}
