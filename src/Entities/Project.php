<?php

namespace Drupal\access_amie\Entities;

use DateTimeInterface;


/**
 *
 */
interface Project {

  /**
   *
   */
  public function isActive(): bool;

  /**
   *
   */
  public function setActive(bool $active): void;

  /**
   *
   */
  public function setEndDate(DateTimeInterface $end): void;

  /**
   *
   */
  public function getLocalId(): string;

  /**
   *
   */
  public function getPi(): ?Account;

  /**
   *
   */
  public function getUsers(): array;

  /**
   *
   */
  public function addUser(Account $user): void;

  /**
   *
   */
  public function transferFunds(int $amount, string $resource): void;

  /**
   *
   */
  public function recoupFunds(): void;

  /**
   *
   */
  public function save(array $body, Account $pi): void;
}
