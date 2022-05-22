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