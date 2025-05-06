<?php

namespace Drupal\access_amie\Packets;


class InactivateAccount extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_account_inactivate', $packet);
  }


  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    $account = Packet::$factory->findAccount($this->data['body']);

    if ($account == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Account not found');
    }

    if ($account->isActive()) {
      $account->setInactive();
    }

    return new NotifyAccountInactivate($this, $account, $project);
  }
}
