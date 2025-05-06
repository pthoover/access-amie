<?php

namespace Drupal\access_amie\Packets;

use DateTimeImmutable;
use DateTimeZone;


class CreateProjectRenewal extends IncomingPacket {

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
    else {
      $end = new DateTimeImmutable($this->data['body']['EndDate'], new DateTimeZone('UTC'));

      $project->recoupFunds();
      $project->setEndDate($end);
    }

    $amount = floatval($this->data['body']['ServiceUnitsAllocated']);

    $project->addTransfer($amount);

    return new NotifyProjectCreate($this, $project);
  }
}
