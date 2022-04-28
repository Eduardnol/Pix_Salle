<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class ProfileController
{
    private Twig $twig;
    private ValidatorService $validator;

    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->validator = new ValidatorService();
    }

    /**
     * Renders the form
     */
    public function showProfileForm(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response,
            'profile.twig',
            [
                'formAction' => $routeParser->urlFor('profile')
            ]
        );
    }

    public function profile(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $errors = [];

        $errors['phoneNumber'] = $this->validator->validatePhoneNumber($data['phoneNumber']);

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
    }


}