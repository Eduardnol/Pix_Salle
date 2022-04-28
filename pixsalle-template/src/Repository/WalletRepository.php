<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\User;

interface WalletRepository
{
	public function getBalance(User $user): int;

	public function addMoney(User $user, int $amount): void;

	public function removeMoney(User $user, int $amount): void;
}
