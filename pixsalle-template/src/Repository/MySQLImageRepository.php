<?php

namespace Salle\PixSalle\Repository;

class MySQLImageRepository implements ImageRepository
{

    public function getImages()
    {
        $query = <<<'QUERY'
        
        SELECT * FROM images;   
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $count = $statement->rowCount();

        if ($count > 0) {
            return $statement->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }
}