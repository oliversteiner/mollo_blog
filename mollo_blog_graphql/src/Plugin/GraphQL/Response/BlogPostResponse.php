<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\Response;

use Drupal\Core\Entity\EntityInterface;
use Drupal\graphql\GraphQL\Response\Response;

/**
 * Type of response used when an blog_post is returned.
 */
class BlogPostResponse extends Response {

  /**
   * The blog_post to be served.
   *
   * @var \Drupal\Core\Entity\EntityInterface|null
   */
  protected $blog_post;

  /**
   * Sets the content.
   *
   * @param \Drupal\Core\Entity\EntityInterface|null $blog_post
   *   The blog_post to be served.
   */
  public function setBlogPost(?EntityInterface $blog_post): void {
    $this->blog_post = $blog_post;
  }

  /**
   * Gets the blog_post to be served.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The blog_post to be served.
   */
  public function blog_post(): ?EntityInterface {
    return $this->blog_post;
  }

  /**
   */
  public function getBlogPosts(): array {
    return [0 => 'test1', 1 => 'test 2'];
  }

}
