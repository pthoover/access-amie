<?php

namespace Drupal\access_amie\Packets;


/**
 *
 */
class MergePerson extends IncomingPacket {

  // constructor


  /**
   *
   */
  public function __construct(array $packet) {
    parent::__construct('request_person_merge', $packet);
  }


  // public methods


  /**
   * {@inheritdoc}
   */
  public function handle(): OutgoingPacket {
  }
}
