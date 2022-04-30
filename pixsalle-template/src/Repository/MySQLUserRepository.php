<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use PDO;
use Salle\PixSalle\Model\User;

final class MySQLUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database)
    {
        $this->databaseConnection = $database;
    }

    public function addInfoUser(User $user): void
    {
        $query = <<<'QUERY'
        UPDATE user SET userName = :userName, phone = :phone, picture = :picture WHERE userId = :user_id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $userName = $user->getUserName();
        $phone = $user->getPhone();
        $picture = $user->getPicture();

        $statement->bindParam('userName', $userName, PDO::PARAM_STR);
        $statement->bindParam('phome', $phone, PDO::PARAM_STR);
        $statement->bindParam('picture', $picture, PDO::PARAM_STR);

        $statement->execute();
    }

    public function createUser(User $user): void
    {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, createdAt, updatedAt)
        VALUES(:email, :password, :createdAt, :updatedAt)
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $email = $user->email();
        $password = $user->password();
        $createdAt = $user->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $user->updatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getUserByEmail(string $email)
    {
        $query = <<<'QUERY'
        SELECT * FROM users WHERE email = :email
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('email', $email, PDO::PARAM_STR);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }
}
