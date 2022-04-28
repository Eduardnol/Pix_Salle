<?php

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\User;

class MySQLWalletRepository implements WalletRepository
{
	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}


	public function getBalance(User $user): int
	{
		// TODO: Implement getBalance() method.
	}

	public function addMoney(User $user, int $amount): void
	{
		// TODO: Implement addMoney() method.
	}

	public function removeMoney(User $user, int $amount): void
	{
		// TODO: Implement removeMoney() method.
	}
}