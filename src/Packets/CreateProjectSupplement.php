<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Project;


class CreateProjectSupplement extends IncomingPacket {

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
    }

    $amount = floatval($this->packet['body']['ServiceUnitsAllocated']);

    $project->addTransfer($amount);

    return new NotifyProjectCreate($this, $project);
  }
}
