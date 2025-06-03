<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Account;
use Drupal\access_amie\Entities\Project;


/**
 *
 */
class NotifyAccountInactivate extends OutgoingPacket {

  // constructor


  /**
   *
   */
  public function __construct(IncomingPacket $packet, Account $account, Project $project) {
    $body = [
      'ProjectID' => $project->getLocalId(),
      'PersonID' => $account->getLocalId(),
      'ResourceList' => $packet->getResourceList()
    ];

    parent::__construct('notify_account_inactivate', $packet, $body);
  }
}
