<?php

namespace Salle\PixSalle\Model;

class UserProfile
{

    private int $id;
    private string $user_name;
    private string $phone_number;
    private string $picture;

    /**
     * @param int $id
     * @param string $user_name
     * @param string $phone_number
     * @param string $picture
     */
    public function __construct(int $id, string $user_name, string $phone_number, string $picture)
    {
        $this->id = $id;
        $this->user_name = $user_name;
        $this->phone_number = $phone_number;
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }


}