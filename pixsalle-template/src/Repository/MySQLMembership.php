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

	public function showCurrentMembership(int $userId)
	{

		$sql = 'SELECT * FROM memberships WHERE userId = :user_id';
		$stmt = $this->databaseConnection->prepare($sql);
		$stmt->execute(['user_id' => $userId]);
		$membership = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($membership === false) {
			return null;
		}

		return $membership;
	}

	public function changeCurrentMembership(int $userId, bool $isActive)
	{
		$sql = 'UPDATE memberships SET isActive = :is_active WHERE userId = :user_id';
		$stmt = $this->databaseConnection->prepare($sql);
		$stmt->execute(['user_id' => $userId, 'is_active' => $isActive]);
	}
}