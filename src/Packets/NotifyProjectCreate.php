<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Project;


/**
 *
 */
class NotifyProjectCreate extends OutgoingPacket {

  // constructor


  /**
   *
   */
  public function __construct(IncomingPacket $packet, Project $project) {
    $pi = $project->getPi();
    $body = [
      'ProjectID' => $project->getLocalId(),
      'PiPersonID' => $pi->getLocalId(),
      'PiRemoteSiteLogin' => $pi->getRemoteSiteLogin()
    ];

    parent::__construct('notify_project_create', $packet, $body);
  }
}
