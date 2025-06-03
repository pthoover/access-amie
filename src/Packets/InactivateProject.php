<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class InactivateProject extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_project_inactivate', $packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    if ($project->isActive()) {
      foreach ($project->getUsers() as $user) {
        if ($user->isActive($project)) {
          $user->setActive(false, $project);
        }
      }

      $project->recoupFunds();
      $project->setActive(false);
    }

    return new NotifyProjectInactivate($this, $project);
  }
}
