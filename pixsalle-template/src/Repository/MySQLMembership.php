<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use PDO;

final class MySQLMembership implements MembershipRepository
{
	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}

	public function showCurrentMembership(string $userId)
	{

		$sql = 'SELECT * FROM memberships WHERE userId = :user_id';
		$stmt = $this->databaseConnection->prepare($sql);
		$stmt->execute(['user_id' => $userId]);
		$membership = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($membership === false) {
			return null;
		}

		return $membership['isActive'];
	}

	public function changeCurrentMembership(string $userId, bool $isActive)
	{
		$sql = 'UPDATE memberships SET isActive = :is_active WHERE userId = :user_id';
		$stmt = $this->databaseConnection->prepare($sql);
		$stmt->execute(['user_id' => $userId, 'is_active' => $isActive]);
	}

	public function insertNewMembership(string $userId)
	{
		$sql = 'INSERT INTO memberships (userId, isActive, createdAt, updatedAt) VALUES (:user_id, 0, NOW(), NOW())';
		$stmt = $this->databaseConnection->prepare($sql);
		$stmt->execute(['user_id' => $userId]);
	}
}