<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

interface WalletRepository
{
	public function getBalance(string $user_id);

	public function addMoney(string $user_id, int $amount): void;

	public function removeMoney(string $user_id, int $amount): void;

	public function insertNewEntry(string $user_id, int $amount): void;
}
