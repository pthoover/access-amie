<?php

namespace Drupal\access_amie\Entities;


interface Account {

  public function getLocalId(): string;
  public function getOrgCode(): string;
  public function getDns(): array;
  public function setDns(array $dns): void;
  public function getRemoteSiteLogin(): string;
  public function setActive(): void;
  public function setInactive(): void;
  public function isActive(): bool;
  public function delete(): void;
}
