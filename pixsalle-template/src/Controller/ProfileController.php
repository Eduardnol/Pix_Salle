<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;
use Salle\PixSalle\Model\User;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;


class ProfileController
{
    private Twig $twig;
    private ValidatorService $validator;
    private UserRepository $userRepository;

    private const UPLOADS_DIR = __DIR__ . '/../../public/assets/img';
    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";
    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";


    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->validator = new ValidatorService();
	}

	/**
	 * Renders the form
	 */
	public function showProfileForm(Request $request, Response $response): Response
	{
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if (isset($_SESSION['user_id'])) {
            $actual_user_id = $_SESSION['user_id'];
            $actual_user_email = $_SESSION['user_email'];
            $user = $this->userRepository->getUserByEmail($actual_user_email);

            return $this->twig->render(
                $response,
                'profile.twig',
                [
	                'formAction' => $routeParser->urlFor('profile'),
	                'userId' => $actual_user_id,
	                'userEmail' => $actual_user_email,
	                'allUser' => $user,
	                'logged' => $_SESSION['logged']
                ]
			);
		} else {
            return $response->withHeader('Location', '/sign-in')->withStatus(302);
		}


	}

	public function profile(Request $request, Response $response): Response
    {
        $error = false;
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        //$user = $this->userRepository->getUserByEmail($data['email']);
        $errors = [];


        $uploadedFiles = $request->getUploadedFiles();

        /** @var UploadedFileInterface $uploadedFile */
        $uploadedFile = $uploadedFiles['files'];

        $name = $uploadedFile->getClientFilename();
        $newName = 'profile_default.png';

        if ($name != NULL) {
            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];
            $size = $uploadedFile->getSize();

            $uuid = Uuid::uuid4();
            $newName = $uuid->toString() . '.' . $format;
            list($width, $height) = getimagesize($_FILES['files']['tmp_name']);

            if (!$this->validator->isValidFormat($format)) {
                $errors['uploadImage'] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
            } elseif ($size > 1000000) {
                $errors['uploadImage'] = "The image must be less than 1MB";
            } elseif ($width > 500 || $height > 500) {
                $errors['uploadImage'] = "The image must be less than 500x500 pixels";
            } else {
                $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $newName);
            }
        }

        $errors['phoneNumber'] = $this->validator->validatePhoneNumber($data['phoneNumber'], $error);
        $errors['userName'] = $this->validator->validateUserName($data['userName'], $error);

        if ($error) {
            $actual_user_id = $_SESSION['user_id'];
            $actual_user_email = $_SESSION['user_email'];
            $user = $this->userRepository->getUserByEmail($actual_user_email);
            return $this->twig->render(
                $response,
                'profile.twig',
                [
                    'formErrors' => $errors,
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor('profile'),
                    'formMethod' => "POST",
                    'logged' => $_SESSION['logged'],
                    'userId' => $actual_user_id,
                    'userEmail' => $actual_user_email,
                    'allUser' => $user,

                ]
			);
		} else {
			$actualUser = $_SESSION['user_id'];
            $date = new DateTime('2000-12-12');
            $user = new User("", "", $date, $date, $data['userName'], $data['phoneNumber'], $newName);
            $this->userRepository->addInfoUser($user, $actualUser);
			return $response->withHeader('Location', '/profile')->withStatus(302);
		}

	}


}