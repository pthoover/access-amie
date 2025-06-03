<?php

namespace Drupal\access_amie\Transactions;

use DateTimeImmutable;
use DateTimeInterface;
use Drupal\access_amie\Packets\PacketRecord;


/**
 *
 */
class Transaction {

  // properties


  /**
   *
   * @var int
   */
  private int $transaction_id;

  /**
   *
   * @var string
   */
  private string $originating_site;

  /**
   *
   * @var string
   */
  private string $remote_site;

  /**
   *
   * @var string
   */
  private string $local_site;

  /**
   *
   * @var string
   */
  private string $state;

  /**
   *
   * @var DateTimeImmutable
   */
  private DateTimeImmutable $timestamp;

  /**
   *
   * @var array
   */
  private array $packets;


  // constructor


  /**
   *
   */
  public function __construct(array $data) {
    $this->transaction_id = intval($data['transaction_id']);
    $this->originating_site = $data['originating_site_name'];
    $this->remote_site = $data['remote_site_name'];
    $this->local_site = $data['local_site_name'];
    $this->state = $data['state'];
    $this->timestamp = new DateTimeImmutable($data['timestamp']);
    $this->packets = [];

    foreach ($data['DATA'] as $packet) {
      $this->packets[] = new PacketRecord($packet);
    }
  }


  // public methods


  /**
   *
   * @return int
   */
  public function getTransactionId(): int {
    return $this->transaction_id;
  }

  /**
   *
   * @return string
   */
  public function getOriginatingSiteName(): string {
    return $this->originating_site;
  }

  /**
   *
   * @return string
   */
  public function getRemoteSiteName(): string {
    return $this->remote_site;
  }

  /**
   *
   * @return string
   */
  public function getLocalSiteName(): string {
    return $this->local_site;
  }

  /**
   *
   * @return string
   */
  public function getTransactionState(): string {
    return $this->state;
  }

  /**
   *
   * @return DateTimeInterface
   */
  public function getTimestamp(): DateTimeInterface {
    return $this->timestamp;
  }

  /**
   *
   * @return array
   */
  public function getPackets(): array {
    return $this->packets;
  }
}
