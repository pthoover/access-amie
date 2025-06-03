<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class CreateProjectTransfer extends CreateProject {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct($packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): OutgoingPacket {
    $project = $this->findProject($this->data['body']);
    $amount = intval($this->data['body']['ServiceUnitsAllocated']);

    if ($project == null) {
      if ($amount < 0) {
        return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
      }

      $pi = $this->findOrCreatePi($this->data['body']);
      $project = $this->createProject($this->data['body'], $pi);
    }

    $resource = $this->data['body']['AllocatedResource'];

    $project->transferFunds($amount, $resource);

    return new NotifyProjectCreate($this, $project);
  }
}
