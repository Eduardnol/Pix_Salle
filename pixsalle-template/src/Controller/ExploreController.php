<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\ImageRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class ExploreController
{
    private Twig $twig;
    private ImageRepository $imageRepository;

    /**
     * @param Twig $twig
     * @param ImageRepository $imageRepository
     */
    public function __construct(Twig $twig, ImageRepository $imageRepository)
    {
        $this->twig = $twig;
        $this->imageRepository = $imageRepository;
    }

    public function showImages(Request $request, Response $response): Response
    {

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $error = NULL;


        if ($_SESSION['logged'] == NULL) {
            return $this->twig->render(
                $response->withHeader('Location', '/sign-in')->withStatus(302),
                'sign-in.twig',
                [
                    'logged' => $_SESSION['logged'],
                    'error' => $error
                ]
            );
        } else {

            $images = $this->imageRepository->getAllImages();

            return $this->twig->render(
                $response,
                'explore.twig',
                [
                    'logged' => $_SESSION['logged'],
                    'images' => $images,
                    'error' => $error
                ]
            );
        }

    }

}