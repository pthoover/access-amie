<?php

namespace Drupal\access_amie\Packets;

use DateTimeImmutable;
use DateTimeZone;


class CreateProjectExtension extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_project_create', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    $end = new DateTimeImmutable($this->data['body']['EndDate'], new DateTimeZone('UTC'));

    $project->setEndDate($end);

    return new NotifyProjectCreate($this, $project);
  }
}
