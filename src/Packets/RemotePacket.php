<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class PacketRecord extends Packet {

  // properties


  /**
   *
   * @var int
   */
  private int $packet_rec_id;


  // constructor


  /**
   *
   */
  public function __construct(array $data) {
    $type = $data['type'];
    $trans_id = intval($data['header']['trans_rec_id']);

    parent::__construct($type, $trans_id, $data);

    $this->packet_rec_id = intval($data['header']['packet_rec_id']);
  }


  // public methods


  /**
   *
   * @return int
   */
  public function getPacketRecordId(): int {
    return $this->packet_rec_id;
  }
}
