<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Project;


class NotifyProjectInactivate extends OutgoingPacket {

  public function __construct(IncomingPacket $packet, Project $project) {
    $body = [
      'ResourceList' => $packet->getResourceList(),
      'ProjectID' => $project->getLocalId()
    ];

    parent::__construct('notify_project_inactivate', $packet, $body);
  }
}
