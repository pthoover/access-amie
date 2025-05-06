<?php

namespace Drupal\access_amie\Packets;


class IncomingTransactionComplete extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('inform_transaction_complete', $packet);
  }


  public function handle(): OutgoingPacket {
    return new OutgoingTransactionComplete($this, StatusCode::Success, 'OK');
  }
}
