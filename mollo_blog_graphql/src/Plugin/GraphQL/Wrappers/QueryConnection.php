<?php

namespace Drupal\mollo_blog_graphql\Plugin\GraphQL\Wrappers;

use Drupal;
use Drupal\Core\Entity\Query\QueryInterface;
use GraphQL\Deferred;

class QueryConnection {

  /**
   * @var \Drupal\Core\Entity\Query\QueryInterface
   */
  protected $query;

  /**
   * QueryConnection constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryInterface $query
   */
  public function __construct(QueryInterface $query) {
    $this->query = $query;
  }

  /**
   * @return int
   */
  public function total(): int {
    $query = clone $this->query;
    $query->range()->count();
    return $query->execute();
  }

  /**
   * @return array|\GraphQL\Deferred
   */
  public function items() {
    $result = $this->query->execute();
    if (empty($result)) {
      return [];
    }

    $buffer = Drupal::service('graphql.buffer.entity');
    $callback = $buffer->add($this->query->getEntityTypeId(), array_values($result));
    return new Deferred(function () use ($callback) {
      return $callback();
    });
  }

}
