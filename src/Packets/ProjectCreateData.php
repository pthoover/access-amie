<?php

namespace Drupal\access_amie\Packets;


class ProjectCreateData extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('data_project_create', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    $pi = $project->getPi();

    $pi->setDns($this->data['body']['DnList']);

    return new OutgoingTransactionComplete($this, StatusCode::Success, 'Transaction succeeded');
  }
}
