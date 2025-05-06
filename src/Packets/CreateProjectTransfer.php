<?php

namespace Drupal\access_amie\Packets;


class CreateProjectTransfer extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_project_create', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);
    $amount = floatval($this->data['body']['ServiceUnitsAllocated']);

    if ($project == null) {
      if ($amount < 0) {
        return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
      }

      $pi = Packet::$factory->findAccount($this->data['body']);

      if ($pi == null) {
        $pi = Packet::$factory->createAccount($this->data['body']);
      }

      $project = Packet::$factory->createProject($pi, $this->data['body']);
    }

    $project->addTransfer($amount);

    return new NotifyProjectCreate($this, $project);
  }
}
