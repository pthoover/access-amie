<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class DeleteUser extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_user_modify', $packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): OutgoingPacket {
    $account = Packet::$factory->findAccount($this->data['body']);

    if ($account == null) {
      return new OutgoingTransactionComplete($this, StatusCode::Failure, 'Account not found');
    }

    $account->delete();

    return new OutgoingTransactionComplete($this, StatusCode::Success, 'OK');
  }
}
