<?php

namespace Salle\PixSalle\Repository;

use PDO;

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
		$query = "SELECT a.title, a.id FROM album as a, portfolios as p  WHERE a.portfolioId = p.id AND p.userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':userId', $userId);
		$statement->execute();

		return $statement->fetchAll();
	}

	/**
	 * @param $albumId
	 * @param $userId
	 * @return mixed
	 */
	public
	function getAlbumPhotosFromUser($albumId, $userId)
	{
		$query = "SELECT i.imagePath, i.id FROM images as i , album as a, portfolios as pf WHERE a.portfolioId = pf.id 
                                                           AND pf.userId = :userId AND a.portfolioId = pf.id AND i.albumId = a.id AND a.id = :albumId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':userId', $userId);
		$statement->bindParam(':albumId', $albumId);
		$statement->execute();

		return $statement->fetchAll();
	}

	/**
	 * @param $userId
	 * @param $albumName
	 * @return mixed
	 */
	public
	function createAlbum($userId, $albumName)
	{
		//get portfolio id
		$query = "SELECT id FROM portfolios WHERE userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':userId', $userId);
		$statement->execute();
		$id = (int)$statement->fetchColumn();


		$query = "INSERT INTO album (title, portfolioId, createdAt, updatedAt) VALUES (:albumName, :portfolioId , NOW(), NOW())";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':albumName', $albumName);
		$statement->bindParam(':portfolioId', $id);
		$statement->execute();

		return $this->databaseConnection->lastInsertId();
	}

	/**
	 * @param $albumId
	 * @param $userId
	 * @return mixed
	 */
	public
	function deleteAlbum($albumId, $userId)
	{
		//Delete all photos from album
		$query = "DELETE FROM images WHERE albumId = :albumId";
		$statement1 = $this->databaseConnection->prepare($query);
		$statement1->bindParam(':albumId', $albumId);
		$statement1->execute();

		//Delete album
		$query = "DELETE FROM album WHERE id = :albumId AND portfolioId = (SELECT id FROM portfolios WHERE userId = :userId)";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':albumId', $albumId);
		$statement->bindParam(':userId', $userId);
		$statement->execute();

	}

	/**
	 * @param $albumId
	 * @param $userId
	 * @param $photoId
	 * @return mixed
	 */
	public function deletePhotoFromAlbum($albumId, $userId, $photoId)
	{
		$query = "DELETE FROM images WHERE  id = :photoId AND albumId = :albumId AND images.userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':albumId', $albumId);
		$statement->bindParam(':userId', $userId);
		$statement->bindParam(':photoId', $photoId);
		$statement->execute();


		return $statement->fetchAll();
	}


	/**
	 * @param $albumId
	 * @param $userId
	 * @param $imageURL
	 * @return mixed
	 */
	public
	function addPhotoToAlbum($albumId, $userId, $imageURL)
	{
		$query = "INSERT INTO images (albumId, imagePath, userId) VALUES (:albumId, :imageUrl, :userId)";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':albumId', $albumId);
		$statement->bindParam(':imageUrl', $imageURL);
		$statement->bindParam(':userId', $userId);
		$statement->execute();

		return $statement->fetchAll();
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	public function checkIfPortfolioExists($userId)
	{
		$query = "SELECT COUNT(*) FROM portfolios WHERE userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':userId', $userId);
        $statement->execute();

        if ($statement->fetchColumn() > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public function checkIfAlbumExists(int $albumId)
    {
        $query = "SELECT COUNT(*) FROM album WHERE id = :id";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $albumId);
        $statement->execute();

        if ($statement->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $userId
     * @param $title
     * @return mixed
     */
    public
    function createPortfolio($userId, $title)
    {
        $query = "INSERT INTO portfolios (title, userId, createdAt, updatedAt) VALUES (:title, :userId, NOW(), NOW())";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':userId', $userId);
		$statement->bindParam(':title', $title);
		$statement->execute();

		return $this->databaseConnection->lastInsertId();
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	public function getPortfolioTitle($userId)
	{
		$query = "SELECT title FROM portfolios WHERE userId = :userId";
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam(':userId', $userId);
		$statement->execute();

		return $statement->fetchColumn();
	}
}