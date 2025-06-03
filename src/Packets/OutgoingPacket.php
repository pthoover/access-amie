<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
abstract class OutgoingPacket extends Packet {

  // properties


  /**
   *
   * @var int
   */
  private int $in_reply_to;


  // constructor


  /**
   *
   */
  protected function __construct(string $type, IncomingPacket $packet, array $body) {
    $data = [
      'type' => $type,
      'header' => ['in_reply_to' => $packet->getPacketRecordId()],
      'body' => $body
    ];

    parent::__construct($type, $packet->getTransactionRecordId(), $data);

    $this->in_reply_to = $packet->getPacketRecordId();
  }


  // public methods


  /**
   *
   */
  public function getReplyTo(): int {
    return $this->in_reply_to;
  }
}
