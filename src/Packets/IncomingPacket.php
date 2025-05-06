<?php

namespace Drupal\access_amie\Packets;


abstract class IncomingPacket extends Packet {

  private array $resources;


  protected function __construct(string $type, array $data) {
    $packet_id = intval($data['header']['packet_rec_id']);
    $trans_id = intval($data['header']['trans_rec_id']);

    if (array_key_exists('ResourceList', $data['body'])) {
      $this->resources = $data['body']['ResourceList'];
    }

    parent::__construct($type, $packet_id, $trans_id, $data);
  }


  abstract public function handle(): ?OutgoingPacket;

  /**
   *
   * @return array
   */
  public function getResourceList(): array {
    return $this->resources;
  }
}
