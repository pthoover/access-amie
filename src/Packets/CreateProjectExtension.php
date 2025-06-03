<?php

namespace Drupal\access_amie\Packets;

use DateTime;


/**
 *
 */
class CreateProjectExtension extends CreateProject {

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
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    $end = new DateTime($this->data['body']['EndDate']);

    $project->setEndDate($end);

    return new NotifyProjectCreate($this, $project);
  }
}
