<?php

namespace Drupal\access_amie\Packets;


class CreateProjectNew extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_project_create', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      $pi = Packet::$factory->findAccount($this->data['body']);

      if ($pi == null) {
        $pi = Packet::$factory->createAccount($this->data['body']);
      }

      $project = Packet::$factory->createProject($pi, $this->data['body']);
      $amount = floatval($this->data['body']['ServiceUnitsAllocated']);

      $project->addTransfer($amount);
    }
    else if (!$project->isActive()) {
      $project->setActive();
    }

    return new NotifyProjectCreate($this, $project);
  }
}
