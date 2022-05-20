<?php

namespace Salle\PixSalle\Repository;

use PDO;
use Salle\PixSalle\Model\Blog;


final class MySQLBlogRepository implements BlogRepository
{

	private PDO $databaseConnection;

	public function __construct(PDO $database)
	{
		$this->databaseConnection = $database;
	}


	public function createBlog(string $title, string $comment, int $userid): Blog
	{
		$query = <<<'QUERY'
        INSERT INTO blogs(title, content, userId, createdAt, updatedAt)
        VALUES(:title, :comment, :userId, NOW(), NOW())
        QUERY;
		$statement = $this->databaseConnection->prepare($query);

		$statement->bindParam('title', $title, PDO::PARAM_STR);
		$statement->bindParam('comment', $comment, PDO::PARAM_STR);
		$statement->bindParam('userId', $userid, PDO::PARAM_STR);

		$statement->execute();
		$id = $this->databaseConnection->lastInsertId();

		$blog = new Blog();
		$blog->setUserId($userid);
		$blog->setTitle($title);
		$blog->setContent($comment);
		$blog->setId($id);

		return $blog;


	}

	public function showBlogs(): bool|array
	{
		$query = <<<'QUERY'
        SELECT b.id,b.title,b.content, b.userId FROM blogs as b;   
        QUERY;
		$statement = $this->databaseConnection->prepare($query);

		$statement->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Salle\PixSalle\Model\Blog');
		return $statement->fetchAll();
	}

}