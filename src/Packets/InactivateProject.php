<?php

namespace Drupal\access_amie\Packets;


class InactivateProject extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_project_inactivate', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    if ($project->isActive()) {
      $project->setInactive();
    }

    return new NotifyProjectInactivate($this, $project);
  }
}
