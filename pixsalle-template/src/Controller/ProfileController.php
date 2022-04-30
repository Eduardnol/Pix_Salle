<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

        return $this->twig->render(
            $response,
            'profile.twig',
            [
                'formAction' => $routeParser->urlFor('profile'),
                'userId' => $actual_user_id,
                'userEmail' => $actual_user_email
            ]
        );
    }

    public function profile(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        //$user = $this->userRepository->getUserByEmail($data['email']);
        $errors = [];

        $errors['phoneNumber'] = $this->validator->validatePhoneNumber($data['phoneNumber']);

        if (count($errors) != 0) {
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
            $this->userRepository->addInfoUser();
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

    }


}