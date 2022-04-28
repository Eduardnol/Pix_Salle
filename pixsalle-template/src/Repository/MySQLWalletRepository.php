<?php

namespace Salle\PixSalle\Repository;

use PDO;

class MySQLWalletRepository implements WalletRepository
{
	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}


	public function getBalance(string $user_id)
	{
		$query = $this->databaseConnection->prepare('SELECT quantity FROM money WHERE userId = :user_id');
		$query->execute([
			'user_id' => $user_id
		]);

		$result = $query->fetch();

		return $result['balance'];
	}

	public function addMoney(string $user_id, int $amount): void
	{
		$query = $this->databaseConnection->prepare('UPDATE money SET quantity = quantity + :amount, updatedAt = NOW() WHERE userId = :user_id');
		$query->execute([
			'amount' => $amount,
			'user_id' => $user_id
		]);
	}

	public function removeMoney(string $user_id, int $amount): void
	{
		$query = $this->databaseConnection->prepare('UPDATE money SET quantity = quantity - :amount, updatedAt = NOW() WHERE userId = :user_id');
		$query->execute([
			'amount' => $amount,
			'user_id' => $user_id
		]);
	}

	public function insertNewEntry(string $user_id, int $amount): void
	{
		$query = $this->databaseConnection->prepare('INSERT INTO money (userId, quantity, updatedAt, createdAt) VALUES (:user_id, :amount, NOW(), NOW())');
		$query->execute([
			'user_id' => $user_id,
			'amount' => $amount
		]);
	}
}