<?php

namespace Drupal\access_amie\Packets;


class CreateAccount extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_account_create', $packet);
  }


  public function handle(): ?OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return null;
    }

    $account = Packet::$factory->findAccount($this->data['body']);

    if ($account == null) {
      $account = Packet::$factory->createAccount($this->data['body']);
    }
    else if (!$account->isActive()) {
      $account->setActive();
    }

    return new NotifyAccountCreate($this, $account, $project);
  }
}
