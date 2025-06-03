<?php

namespace Drupal\access_amie\Entities;


/**
 *
 */
interface EntityFactory {

  /**
   *
   */
  public function findAccount(array $body): ?Account;

  /**
   *
   */
  public function createAccount(): Account;

  /**
   *
   */
  public function findProject(array $body): ?Project;

  /**
   *
   */
  public function createProject(): Project;
}
