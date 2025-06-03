<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class ReactivateProject extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_project_reactivate', $packet);
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

    if (!$project->isActive()) {
      $pi = $project->getPi();

      $project->setActive(true);
      $pi->setActive(true, $project);

      $amount = intval($this->data['body']['ServiceUnitsAllocated']);
      $resource = $this->data['body']['AllocatedResource'];

      $project->transferFunds($amount, $resource);
    }

    return new NotifyProjectReactivate($this, $project);
  }
}
