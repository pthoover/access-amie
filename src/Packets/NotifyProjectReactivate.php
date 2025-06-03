<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Project;


/**
 *
 */
class NotifyProjectReactivate extends OutgoingPacket {

  // constructor


  /**
   *
   */
  public function __construct(IncomingPacket $packet, Project $project) {
    $body = [
      'ResourceList' => $packet->getResourceList(),
      'ProjectID' => $project->getLocalId()
    ];

    parent::__construct('notify_project_reactivate', $packet, $body);
  }
}
