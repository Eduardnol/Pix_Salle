<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Model\User;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class SignUpController
{
    private Twig $twig;
    private ValidatorService $validator;
    private UserRepository $userRepository;

    public function __construct(
        Twig $twig,
        UserRepository $userRepository
    ) {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->validator = new ValidatorService();
    }

    /**
     * Renders the form
     */
    public function showSignUpForm(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
	    $_SESSION['logged'] = 0;

        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'logged' => $_SESSION['logged'],
                'formAction' => $routeParser->urlFor('signUp')
            ]
        );
    }

    public function signUp(Request $request, Response $response): Response
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
        
        $savedUser = $this->userRepository->getUserByEmail($data['email']);
        if ($savedUser != null) {
            $errors['email'] = "User already exists!";
        }
        if (count($errors) == 0) {
            $user = new User($data['email'], md5($data['password']), new DateTime(), new DateTime());
            $this->userRepository->createUser($user);
            return $response->withHeader('Location', '/sign-in')->withStatus(302);
        }
        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'logged' => $_SESSION['logged'],
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor('signUp')
            ]
        );
    }
}
