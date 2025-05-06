<?php

namespace Drupal\access_amie\Packets;


enum StatusCode {
  case Success;
  case Failure;
}

class OutgoingTransactionComplete extends OutgoingPacket {

  public function __construct(IncomingPacket $packet, StatusCode $status, string $message) {
    if ($status == StatusCode::Success) {
      $status_code = 'Success';
      $detail_code = 1;
    }
    else {
      $status_code = 'Failure';
      $detail_code = 2;
    }

    $body = [
      'StatusCode' => $status_code,
      'DetailCode' => $detail_code,
      'Message' => $message
    ];

    parent::__construct('inform_transaction_complete', $packet, $body);
  }
}
