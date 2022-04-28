<?php

namespace Salle\PixSalle\Repository;

use PDO;
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
		$query = $this->databaseConnection->prepare('UPDATE money SET quantity = quantity + :amount, updatedAt = NOW() WHERE userId = :user_id');
		$query->execute([
			'amount' => $amount,
			'user_id' => $user->id()
		]);
	}

	public function removeMoney(User $user, int $amount): void
	{
		$query = $this->databaseConnection->prepare('UPDATE money SET quantity = quantity - :amount, updatedAt = NOW() WHERE userId = :user_id');
		$query->execute([
			'amount' => $amount,
			'user_id' => $user->id()
		]);
	}

	public function insertNewEntry(User $user, int $amount): void
	{
		$query = $this->databaseConnection->prepare('INSERT INTO money (userId, quantity, updatedAt, createdAt) VALUES (:user_id, :amount, NOW(), NOW())');
		$query->execute([
			'user_id' => $user->id(),
			'amount' => $amount
		]);
	}
}