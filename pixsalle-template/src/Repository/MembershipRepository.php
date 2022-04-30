<?php

namespace Salle\PixSalle\Repository;

interface MembershipRepository
{

	public function showCurrentMembership(string $userId);

	public function changeCurrentMembership(string $userId, bool $isActive);

	public function insertNewMembership(string $userId);

}