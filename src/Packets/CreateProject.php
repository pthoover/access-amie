<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Account;
use Drupal\access_amie\Entities\Project;


/**
 *
 */
abstract class CreateProject extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_project_create', $packet);
  }


  // protected methods


  /**
   *
   */
  protected function findProject(array $body): ?Project {
    $project = Packet::$factory->findProject($body);

    if ($project != null && !$project->isActive()) {
      $project->setActive(true);
    }

    return $project;
  }

  /**
   *
   */
  protected function createProject(array $body, Account $pi): Project {
    $project = Packet::$factory->createProject();

    $project->save($body, $pi);

    if (!$pi->isActive($project)) {
      $pi->setActive(true, $project);
    }

    return $project;
  }

  /**
   *
   */
  protected function findOrCreatePi(array $body): Account {
    $pi = Packet::$factory->findAccount($body);

    if ($pi == null) {
      $pi = Packet::$factory->createAccount();

      $pi->save($body);
    }

    return $pi;
  }
}
