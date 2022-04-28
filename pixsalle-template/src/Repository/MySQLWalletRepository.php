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

	/**
	 * @param User $user
	 * @return int
	 */
	public function getBalance(User $user): int
	{
		$query = $this->databaseConnection->prepare('SELECT quantity FROM money WHERE userId = :user_id');
		$query->execute([
			'user_id' => $user->id()
		]);

		$result = $query->fetch();

		return $result['balance'];
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