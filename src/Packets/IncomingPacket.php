<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
abstract class IncomingPacket extends Packet {

  // properties


  /**
   *
   * @var int
   */
  private int $packet_rec_id;

  /**
   *
   * @var array
   */
  private array $resources;


  // constructor


  /**
   *
   */
  protected function __construct(string $type, array $data) {
    $trans_id = intval($data['header']['trans_rec_id']);

    parent::__construct($type, $trans_id, $data);

    $this->packet_rec_id = intval($data['header']['packet_rec_id']);

    if (array_key_exists('ResourceList', $data['body'])) {
      $this->resources = $data['body']['ResourceList'];
    }
  }


  // public methods


  /**
   *
   */
  abstract public function handle(): ?OutgoingPacket;

  /**
   *
   * @return int
   */
  public function getPacketRecordId(): int {
    return $this->packet_rec_id;
  }

  /**
   *
   * @return array
   */
  public function getResourceList(): array {
    return $this->resources;
  }
}
