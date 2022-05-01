<?php

namespace Salle\PixSalle\Model;

class Image
{
    private int $id;
    private int $userId;
    private string $imagePath;

    public function __construct(
        int    $userId,
        string $imagePath
    )
    {
        $this->userId = $userId;
        $this->imagePath = $imagePath;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

}