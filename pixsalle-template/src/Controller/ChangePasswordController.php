<?php

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
                'formAction' => $routeParser->urlFor('profile/changePassword'),
            ]
        );
    }

    public function changePass(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $errors = [];

        return '';
    }


}