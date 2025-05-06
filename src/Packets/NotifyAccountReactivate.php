<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\Account;
use Drupal\access_amie\Entities\Project;


class NotifyAccountReactivate extends OutgoingPacket {

  public function __construct(IncomingPacket $packet, Account $account, Project $project) {
    $body = [
      'ProjectID' => $project->getLocalId(),
      'PersonID' => $account->getLocalId(),
      'ResourceList' => $packet->getResourceList()
    ];

    parent::__construct('notify_account_reactivate', $packet, $body);
  }
}
