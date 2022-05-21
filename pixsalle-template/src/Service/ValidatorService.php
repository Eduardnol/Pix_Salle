<?php

declare(strict_types=1);

namespace Salle\PixSalle\Service;

class ValidatorService
{

    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['png', 'jpg'];


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

    public function validateUserName(string $userName, bool &$error)
    {
        $userName_format = "/[A-Za-z][0-9]|[0-9][A-Za-z]/";

        if (!empty($userName)) {
            if (!preg_match($userName_format, $userName)) {
                $error = true;
                return 'The user name must be alphanumeric!';
            }
        } else {
            $error = true;
            return 'The user name must be alphanumeric!';
        }
        return '';
    }

    public function validatePhoneNumber(string $phoneNumber, bool &$error)
    {
        $phone_format = "/(6)([0-9]){8}/";


        if (!empty($phoneNumber)) {
            if (strlen($phoneNumber) != 9) {
                $error = true;
                return 'The phone number must contain 9 numbers.';
            } elseif (!preg_match($phone_format, $phoneNumber)) {
                $error = true;
                return 'The phone number must start by 6.';
            }
        }
        return '';
    }

    public function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

    public function matchingPasswords($pass1, $pass2)
    {
        if (strcmp($pass1, $pass2) == 0) {
            return true;
        } else {
            return false;
        }
    }

	public function validateQuantity(string $quantity)
	{
		if (empty($quantity) || !is_numeric($quantity) || $quantity < 1) {
            return 'The quantity must be a positive number';
        } else if ($quantity > 100000) {
            return "The quantity can't exceed 100000";
        }
		return '';
	}
}
