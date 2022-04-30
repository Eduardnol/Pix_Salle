<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

        $actual_user_id = $_SESSION['user_id'];
        $actual_user_email = $_SESSION['user_email'];

        if ($actual_user_id) {
            return $this->twig->render(
                $response,
                'profile.twig',
                [
                    'formAction' => $routeParser->urlFor('profile'),
                    'userId' => $actual_user_id,
                    'userEmail' => $actual_user_email
                ]
            );
        } else {
            return $response->withHeader('Location', '/log-in')->withStatus(302);
        }


    }

    public function profile(Request $request, Response $response): Response
    {
        $error = false;
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        //$user = $this->userRepository->getUserByEmail($data['email']);
        $errors = [];

        $errors['phoneNumber'] = $this->validator->validatePhoneNumber($data['phoneNumber'], $error);

        if ($error) {
            return $this->twig->render(
                $response,
                'sign-in.twig',
                [
                    'formErrors' => $errors,
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor('profile'),
                    'formMethod' => "POST"
                ]
            );
        } else {
            $actualUser = $_SESSION['user_id'];
            $date = new \DateTime('2000-12-12');
            $user = new User("", "", $date, $date, $data['userName'], $data['phoneNumber'], "hola");
            $this->userRepository->addInfoUser($user, $actualUser);
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

    }


}