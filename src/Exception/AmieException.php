<?php

namespace Drupal\access_amie\Exception;

use RuntimeException;
use Throwable;


class AmieException extends RuntimeException {

  public function __construct(string $message, ?Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
  }
}
