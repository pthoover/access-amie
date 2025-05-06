<?php

namespace Drupal\access_amie\Packets;


class ModifyUser extends IncomingPacket {

  public function __construct(array $packet) {
    parent::__construct('request_user_modify', $packet);
  }


  public function handle(): OutgoingPacket {
  }
}
