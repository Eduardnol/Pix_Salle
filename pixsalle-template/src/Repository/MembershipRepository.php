<?php

namespace Salle\PixSalle\Repository;

interface MembershipRepository
{

	public function showCurrentMembership(int $userId);

	public function changeCurrentMembership(int $userId);

}