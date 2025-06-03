<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Account;
use Drupal\access_amie\Entities\Project;


/**
 *
 */
class NotifyAccountCreate extends OutgoingPacket {

  // constructor


  /**
   *
   */
  public function __construct(IncomingPacket $packet, Account $account, Project $project) {
    $body = [
      'ProjectID' => $project->getLocalId(),
      'UserPersonID' => $account->getLocalId(),
      'ResourceList' => $packet->getResourceList(),
      'UserRemoteSiteLogin' => $account->getLogin()
    ];

    parent::__construct('notify_account_create', $packet, $body);
  }
}
