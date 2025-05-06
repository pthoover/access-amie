<?php

namespace Drupal\access_amie\Entities;

use DateTimeInterface;


interface Project {

  public function getLocalId(): string;
  public function getTitle(): string;
  public function getGrantNumber(): string;
  public function getStartDate(): DateTimeInterface;
  public function setStartDate(DateTimeInterface $start): void;
  public function getEndDate(): DateTimeInterface;
  public function setEndDate(DateTimeInterface $end): void;
  public function getPi(): Account;
  public function addTransfer(float $amount): void;
  public function getSusAllocated(): float;
  public function recoupFunds(): void;
  public function setActive(): void;
  public function setInactive(): void;
  public function isActive(): bool;
  public function getPfosNumber(): string;
}
