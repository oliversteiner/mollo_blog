<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\Response;

use Drupal\Core\Entity\EntityInterface;
use Drupal\graphql\GraphQL\Response\Response;

/**
 * Type of response used when an mollo_blog is returned.
 */
class MolloBlogResponse extends Response {

  /**
   * The mollo_blog to be served.
   *
   * @var \Drupal\Core\Entity\EntityInterface|null
   */
  protected $mollo_blog;

  /**
   * Sets the content.
   *
   * @param \Drupal\Core\Entity\EntityInterface|null $entity
   *   The mollo_blog to be served.
   */
  public function setMolloBlog(?EntityInterface $entity): void {
    $this->mollo_blog = $entity;
  }

  /**
   * Gets the mollo_blog to be served.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The mollo_blog to be served.
   */
  public function mollo_blog(): ?EntityInterface {
    return $this->mollo_blog;
  }


}
