<?php

namespace Salle\PixSalle\Repository;

class MySQLPortfolioRepository implements PortfolioRepository
{
	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}

	/**
	 * Gets all the albums of a certain user inside a repository.
	 * @param $userId
	 * @return mixed
	 */
	public function getAllUserAlbums($userId)
	{
		$query = "SELECT * FROM album as a, portfolios as p  WHERE a.portfolioId = p.id AND p.userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':user_id', $userId);
		$statement->execute();

		return $statement->fetchAll();
	}


}

/**
 * @param $albumId
 * @param $userId
 * @return mixed
 */
public
function getAlbumPhotosFromUser($albumId, $userId)
{
	// TODO: Implement getAlbumPhotosFromUser() method.
}

/**
 * @param $userId
 * @param $albumName
 * @return mixed
 */
public
function createAlbum($userId, $albumName)
{
	// TODO: Implement createAlbum() method.
}

/**
 * @param $albumId
 * @param $userId
 * @return mixed
 */
public
function deleteAlbumOrPhoto($albumId, $userId)
{
	// TODO: Implement deleteAlbumOrPhoto() method.
}

/**
 * @param $albumId
 * @param $userId
 * @param $photoId
 * @return mixed
 */
public
function addPhotoToAlbum($albumId, $userId, $photoId)
{
	// TODO: Implement addPhotoToAlbum() method.
}

/**
 * @param $userId
 * @return mixed
 */
public
function checkIfPortfolioExists($userId)
{
	// TODO: Implement checkIfPortfolioExists() method.
}

/**
 * @param $userId
 * @return mixed
 */
public
function createPortfolio($userId)
{
	// TODO: Implement createPortfolio() method.
}
}