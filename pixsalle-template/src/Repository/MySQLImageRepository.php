<?php

namespace Salle\PixSalle\Repository;

use PDO;

final class MySQLImageRepository implements ImageRepository
{

	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}


	public function getImages()
	{
		$query = <<<'QUERY'
        
        SELECT imagePath FROM images;   
        QUERY;

		$statement = $this->databaseConnection->prepare($query);

		$statement->execute();

		$count = $statement->rowCount();

		if ($count > 0) {
			return $statement->fetch(PDO::FETCH_OBJ);
		}
		return null;
	}

	public function getAllImages(): array
	{
		$aux = 1;
		$images = [];
		$query = <<<'QUERY'
        SELECT i.imagePath,u.userName FROM images as i INNER JOIN users as u ON i.userId = u.id;   
        QUERY;

		$statement = $this->databaseConnection->prepare($query);

		$statement->execute();
		$final = $statement->fetchAll();
		return $final;
	}

}
