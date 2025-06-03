<?php

namespace Drupal\access_amie\Entities;


/**
 *
 */
interface Account {

  /**
   *
   */
  public function isActive(Project $project): bool;

  /**
   *
   */
  public function setActive(bool $active, Project $project): void;

  /**
   *
   */
  public function setDns(array $dns): void;

  /**
   *
   */
  public function getLocalId(): string;

  /**
   *
   */
  public function getLogin(): string;

  /**
   *
   */
  public function save(array $body): void;

  /**
   *
   */
  public function delete(): void;
}
