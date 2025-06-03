<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class CreateProjectNew extends CreateProject {

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
    $pi = $this->findOrCreatePi($this->data['body']);
    $project = $this->findProject($this->data['body']);

    if ($project == null) {
      $project = $this->createProject($this->data['body'], $pi);
    }
    else {
      $project->save($this->data['body'], $pi);
    }

    $amount = intval($this->data['body']['ServiceUnitsAllocated']);
    $resource = $this->data['body']['AllocatedResource'];

    $project->transferFunds($amount, $resource);

    return new NotifyProjectCreate($this, $project);
  }
}
