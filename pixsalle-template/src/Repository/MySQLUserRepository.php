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

	public function addInfoUser(User $user, string $actual): void
	{
		$query = 'UPDATE users SET userName = :userName, phone = :phone, picture = :picture WHERE id = :actual_id';

		$statement = $this->databaseConnection->prepare($query);

		$userName = $user->getUserName();
		$phone = $user->getPhone();
		$picture = $user->getPicture();

		$statement->bindParam('userName', $userName, PDO::PARAM_STR);
		$statement->bindParam('phone', $phone, PDO::PARAM_STR);
		$statement->bindParam('picture', $picture, PDO::PARAM_STR);
		$statement->bindParam('actual_id', $actual, PDO::PARAM_STR);

		$statement->execute();
	}

	public function changePassword(User $user, string $actual): void
	{
		$query = 'UPDATE users SET password = :password WHERE id = :actual_id';

		$statement = $this->databaseConnection->prepare($query);

		$newPass = $user->password();

		$statement->bindParam('password', $newPass, PDO::PARAM_STR);
		$statement->bindParam('actual_id', $actual, PDO::PARAM_STR);

		$statement->execute();
	}

	public function checkOldPassword(string $actual, string $actualPassword): bool
	{

		$query = $this->databaseConnection->prepare('SELECT password FROM users WHERE id = :actual_id');

		$query->execute([
			'actual_id' => $actual
		]);

		$result = $query->fetch();
		$pass = $result['password'];

		if (md5($actualPassword) == $pass) {
			return true;
		} else {
			return false;
		}
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
		$lastId = $this->databaseConnection->lastInsertId();


		$query = 'UPDATE users SET userName = :usernameId WHERE id = :lastid';
		$statement = $this->databaseConnection->prepare($query);
        $str = "user" . $lastId;
        $statement->bindParam('usernameId', $str, PDO::PARAM_STR);
		$statement->bindParam('lastid', $lastId, PDO::PARAM_STR);
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
