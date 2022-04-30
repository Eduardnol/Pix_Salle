<?php

declare(strict_types=1);

namespace Salle\PixSalle\Model;

use DateTime;

class User
{

    private int $id;
    private string $email;
    private string $password;
    private Datetime $createdAt;
    private Datetime $updatedAt;
    private string $userName;
    private string $phone;
    private string $picture;


    public function __construct(
        string   $email,
        string   $password,
        Datetime $createdAt,
        Datetime $updatedAt,
        string   $userName,
        string   $phone,
        string   $picture

    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userName = $userName;
        $this->phone = $phone;
        $this->picture = $picture;
    }

  public function id()
  {
    return $this->id;
  }

  public function email()
  {
    return $this->email;
  }

  public function password()
  {
    return $this->password;
  }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public function updatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }


}
