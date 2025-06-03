<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class ReactivateAccount extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_account_reactivate', $packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): OutgoingPacket {
    $project = Packet::$factory->findProject($this->data['body']);

    if ($project == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Project not found');
    }

    $account = Packet::$factory->findAccount($this->data['body']);

    if ($account == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Account not found');
    }

    if (!$account->isActive($project)) {
      $account->setActive(true, $project);
    }

    return new NotifyAccountReactivate($this, $account, $project);
  }
}
