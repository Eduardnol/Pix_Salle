<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\User;

interface UserRepository
{
    public function createUser(User $user): void;

    public function addInfoUser(User $user, string $actual): void;

    public function getUserByEmail(string $email);

    public function checkOldPassword(string $actual, string $actualPassword);

    public function changePassword(User $user, string $actual): void;
}
