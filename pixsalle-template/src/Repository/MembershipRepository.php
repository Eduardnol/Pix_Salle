<?php

namespace Salle\PixSalle\Repository;

interface MembershipRepository
{

	public function showCurrentMembership(string $userId);

	public function changeCurrentMembership(string $userId, int $isActive);

	public function insertNewMembership(string $userId);

}