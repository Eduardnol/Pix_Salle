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


    public function getImages(): array
    {
        $aux = 1;
        $images = [];
        $query = <<<'QUERY'
        SELECT i.imagePath,u.userName FROM images as i, users as u where i.userId = u.id;   
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        while ($row = $statement->fetch()) {
            $aux2 = 1;
            $images[$aux][$aux2] = array($row['imagePath']);
            $aux2 = 2;

            if (array($row['userName']) == NULL) {
                $images[$aux][$aux2] = 'user' . $_SESSION['user_id'];
            } else {
                $images[$aux][$aux2] = array($row['userName']);
            }
            $aux = $aux + 1;
        }

        return $images;
    }
}