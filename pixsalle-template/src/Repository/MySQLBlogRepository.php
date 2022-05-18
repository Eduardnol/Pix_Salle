<?php

namespace Salle\PixSalle\Repository;

use PDO;

final class MySQLBlogRepository implements BlogRepository
{

    private PDO $databaseConnection;

    public function __construct(PDO $database)
    {
        $this->databaseConnection = $database;
    }


    public function createBlog(string $title, string $comment): void
    {
        $query = <<<'QUERY'
        INSERT INTO blogs(title, comment, userId)
        VALUES(:title, :comment, :userId)
        QUERY;
        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('title', $title, PDO::PARAM_STR);
        $statement->bindParam('comment', $comment, PDO::PARAM_STR);
        $statement->bindParam('userId', $_SESSION['user_id'], PDO::PARAM_STR);

        $statement->execute();
    }

    public function showBlogs()
    {
        $aux = 1;
        $blogs = [];
        $query = <<<'QUERY'
        SELECT b.title,u.userName FROM blogs as b, users as u where b.userId = u.id;   
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        while ($row = $statement->fetch()) {
            $aux2 = 1;
            $blogs[$aux][$aux2] = array($row['title']);
            $aux2 = 2;
            $blogs[$aux][$aux2] = array($row['userName']);
            $aux = $aux + 1;
        }

        return $blogs;
    }
}