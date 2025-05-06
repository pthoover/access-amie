<?php

namespace Drupal\access_amie\Packets;


class ReactivateProject extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_project_reactivate', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    if (!$project->isActive()) {
      $project->setActive();
    }

    return new NotifyProjectReactivate($this, $project);
  }
}
