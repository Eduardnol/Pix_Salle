<?php

namespace Salle\PixSalle\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Model\User;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class ChangePasswordController
{
	private Twig $twig;
	private ValidatorService $validator;
	private UserRepository $userRepository;

	/**
	 * @param Twig $twig
	 */
	public function __construct(Twig $twig, UserRepository $userRepository)
	{
		$this->twig = $twig;
		$this->userRepository = $userRepository;
		$this->validator = new ValidatorService();
	}

	public function showPasswordForm(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		return $this->twig->render(
			$response,
			'changePassword.twig',
			[
				'formAction' => $routeParser->urlFor('changePassword'),
			]
		);
	}

	public function changePass(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		$errors = [];
		$old_error = false;
		$match_error = false;

		$actualUser = $_SESSION['user_id'];
		$password = $data['oldPassword'];


		if ($this->userRepository->checkOldPassword($actualUser, $password)) {
			$errors['oldPassword'] = 'This password is incorrect';
			$old_error = true;
		} else if ($this->validator->matchingPasswords($data['newPassword'], $data['confirmPassword'])) {
			$errors['passDoNotMatch'] = 'This password is incorrect';
			$match_error = true;
		}

		if (!$old_error && !$match_error) {
			return $this->twig->render(
				$response,
				'profile.twig',
				[
					'formErrors' => $errors,
					'formData' => $data,
					'formAction' => $routeParser->urlFor('profile'),
					'formMethod' => "POST"
				]
			);
		} else {
			$password = md5($data['newPassword']);
			$date = new DateTime('2000-12-12');
			$user = new User("", $password, $date, $date, "", "", "");
			$this->userRepository->changePassword($user, $actualUser);
			return $response->withHeader('Location', '/profile')->withStatus(302);
		}

		return '';
	}


}