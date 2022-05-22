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

	public function showSpecificBlog(int $blogId): bool|Blog
	{
		$query = <<<'QUERY'
        SELECT b.id,b.title,b.content, b.userId FROM blogs as b WHERE b.id = :blogId;   
        QUERY;
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam('blogId', $blogId);

		$statement->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Salle\PixSalle\Model\Blog');
		$value = $statement->fetch();
		if ($statement->rowCount() <= 0) {
			return false;
		}
		return $value;
	}

	public function deleteSpecificBlog(int $blogId, int $userId): bool|array
	{
		$query = <<<'QUERY'
		DELETE FROM blogs WHERE id = :blogId AND userId = :userId;   
		QUERY;
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam('blogId', $blogId);
		$statement->bindParam('userId', $userId);

		$statement->execute();

		if ($statement->rowCount() <= 0) {
			return false;
		}


		return true;
	}

	public function updateSpecificBlog(int $blogId, string $title, string $content, int $userId): Blog|array
	{
		$query = <<<'QUERY'
		UPDATE blogs SET title = :title, content = :content WHERE id = :blogId AND userId = :userId;;   
		QUERY;
		$statement = $this->databaseConnection->prepare($query);
		$statement->bindParam('blogId', $blogId);
		$statement->bindParam('title', $title);
		$statement->bindParam('content', $content);
		$statement->bindParam('userId', $userId);

		$statement->execute();
		if ($statement->rowCount() == 0) {
			return array("message" => "Blog entry with id {$blogId} does not exist");
		}


		$blog = new Blog();
		$blog->setUserId($userId);
		$blog->setTitle($title);
		$blog->setContent($content);
		$blog->setId($blogId);

		return $blog;
	}

}