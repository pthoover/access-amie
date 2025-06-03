<?php

namespace Drupal\access_amie\Packets;

use DateTime;


/**
 *
 */
class CreateProjectRenewal extends CreateProject {

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

    if ($project == null) {
      $pi = $this->findOrCreatePi($this->data['body']);
      $project = $this->createProject($this->data['body'], $pi);
    }
    else {
      $project->recoupFunds();
      $project->setEndDate(new DateTime($this->data['body']['EndDate']));
    }

    $amount = intval($this->data['body']['ServiceUnitsAllocated']);
    $resource = $this->data['body']['AllocatedResource'];

    $project->transferFunds($amount, $resource);

    return new NotifyProjectCreate($this, $project);
  }
}
