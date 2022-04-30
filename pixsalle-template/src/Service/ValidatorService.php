<?php

declare(strict_types=1);

namespace Salle\PixSalle\Service;

class ValidatorService
{
  public function __construct()
  {
  }

    public function validateEmail(string $email)
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'The email address is not valid';
        } else if (!strpos($email, "@salle.url.edu")) {
            return 'Only emails from the domain @salle.url.edu are accepted.';
        }
        return '';
    }

    public function validatePassword(string $password)
    {
        if (empty($password) || strlen($password) < 6) {
            return 'The password must contain at least 6 characters.';
        } else if (!preg_match("~[0-9]+~", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password)) {
            return 'The password must contain both upper and lower case letters and numbers';
        }
        return '';
    }

    public function validatePhoneNumber(string $phoneNumber, bool &$edu)
    {
        $phone_format = "/(6)*([0-9])$/";


        if (!empty($phoneNumber)) {
            if (strlen($phoneNumber) != 9) {
                $edu = true;
                return 'The phone number must contain 9 numbers.';
            } elseif (!preg_match($phone_format, $phoneNumber)) {
                $edu = true;
                return 'The phone number must start by 6.';
            }
        }
        return '';
    }

    public function matchingPasswords($pass1, $pass2)
    {
        $match = false;
        if (strcmp($pass1, $pass2) == 0) {
            $match = true;
        } else {
            $match = false;
        }
        return $match;
    }
}
