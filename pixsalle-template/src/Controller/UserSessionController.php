<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class UserSessionController
{
	private Twig $twig;
	private ValidatorService $validator;
	private UserRepository $userRepository;

	public function __construct(
		Twig           $twig,
		UserRepository $userRepository
	)
	{
		$this->twig = $twig;
		$this->userRepository = $userRepository;
		$this->validator = new ValidatorService();
	}

	public function showSignInForm(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$_SESSION['logged'] = 0;
		return $this->twig->render(
			$response, 'sign-in.twig',
			[
				'logged' => $_SESSION['logged'],
				'formAction' => $routeParser->urlFor('signIn')
			]
		);
	}

	public function signIn(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		$errors = [];

		$errors['email'] = $this->validator->validateEmail($data['email']);
		$errors['password'] = $this->validator->validatePassword($data['password']);

        if ($errors['email'] == '') {
            unset($errors['email']);
        }
        if ($errors['password'] == '') {
            unset($errors['password']);
        }
        if (count($errors) == 0) {
            // Check if the credentials match the user information saved in the database
            $user = $this->userRepository->getUserByEmail($data['email']);
            if ($user == null) {
                $errors['email'] = 'User with this email address does not exist.';
            } else if ($user->password != md5($data['password'])) {
                $errors['password'] = 'Your email and/or password are incorrect.';
            } else {
                $_SESSION['user_id'] = $user->id;
	            $_SESSION['user_email'] = $user->email;
	            $_SESSION['logged'] = 1;
                return $response->withHeader('Location','/explore')->withStatus(302);
            }
        }
        return $this->twig->render(
            $response,
            'sign-in.twig',
            [
	            'logged' => $_SESSION['logged'],
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor('signIn')
            ]
        );
    }
}