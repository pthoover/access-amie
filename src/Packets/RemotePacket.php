<?php

namespace Drupal\access_amie\Packets;


class RemotePacket extends Packet {

  public function __construct(array $data) {
    $type = $data['type'];
    $packet_id = intval($data['header']['packet_rec_id']);
    $trans_id = intval($data['header']['trans_rec_id']);

    parent::__construct($type, $packet_id, $trans_id, $data);
  }
}
