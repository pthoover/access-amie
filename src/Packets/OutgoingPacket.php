<?php

namespace Drupal\access_amie\Packets;


abstract class OutgoingPacket extends Packet {

  protected function __construct(string $type, IncomingPacket $packet, array $body) {
    $data = [
      'type' => $type,
      'header' => ['in_reply_to' => $packet->getPacketRecordId()],
      'body' => $body
    ];

    parent::__construct($type, $packet->getPacketRecordId(), $packet->getTransactionRecordId(), $data);
  }
}
