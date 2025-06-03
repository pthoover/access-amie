<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class CreateAccount extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_account_create', $packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): ?OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return null;
    }

    $account = Packet::$factory->findAccount($this->data['body']);

    if ($account == null) {
      $account = Packet::$factory->createAccount();

      $account->save($this->data['body']);
    }
    else if (!$account->isActive($project)) {
      $account->setActive(true, $project);
    }

    $project->addUser($account);

    return new NotifyAccountCreate($this, $account, $project);
  }
}
