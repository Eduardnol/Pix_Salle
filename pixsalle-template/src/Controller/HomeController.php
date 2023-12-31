<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController
{
    private Twig $twig;

    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function showHome(Request $request, Response $response): Response
    {
        return $this->twig->render(
            $response,
            'home.twig',
            [
                'logged' => $_SESSION['logged']
            ]
        );
    }

}