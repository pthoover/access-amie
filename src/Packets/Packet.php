<?php

namespace Drupal\access_amie\Packets;

use Drupal\access_amie\Entities\EntityFactory;


/**
 *
 */
abstract class Packet {

  // properties


  /**
   *
   * @var EntityFactory
   */
  protected static EntityFactory $factory;

  /**
   *
   * @var string
   */
  protected string $packet_type;

  /**
   *
   * @var int
   */
  protected int $trans_rec_id;

  /**
   *
   * @var array
   */
  protected array $data;


  // constructor


  /**
   *
   */
  protected function __construct(string $type, int $trans_id, array $data) {
    $this->packet_type = $type;
    $this->trans_rec_id = $trans_id;
    $this->data = $data;
  }


  // public methods


  /**
   *
   * @param EntityFactory $factory
   */
  public static function setEntityFactory(EntityFactory $factory): void {
    self::$factory = $factory;
  }

  /**
   *
   * @return string
   */
  public function getPacketType(): string {
    return $this->packet_type;
  }

  /**
   *
   * @return int
   */
  public function getTransactionRecordId(): int {
    return $this->trans_rec_id;
  }

  /**
   *
   * @return array
   */
  public function getData(): array {
    return $this->data;
  }

  /**
   *
   * @return string
   */
  public function toJson(): string {
    return json_encode($this->data);
  }
}
