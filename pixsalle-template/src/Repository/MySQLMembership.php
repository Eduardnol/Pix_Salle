<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use PDO;

final class MySQLMembership implements MembershipRepository
{
	public function showCurrentMembership(int $userId)
	{
		$pdo = $this->getPDO();
		$sql = 'SELECT * FROM membership WHERE user_id = :user_id';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['user_id' => $userId]);
		$membership = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($membership === false) {
			return null;
		}

		return $membership;
	}

	public function changeCurrentMembership(int $userId)
	{
		// TODO: Implement changeCurrentMembership() method.
	}
}