<?php

namespace Salle\PixSalle\Controller;

use Salle\PixSalle\Repository\PortfolioRepository;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PortfolioController
{
	private Twig $twig;
	private PortfolioRepository $portfolioRepository;

	/**
	 * @param Twig $twig
	 * @param PortfolioRepository $portfolioRepository
	 */
	public function __construct(Twig $twig, PortfolioRepository $portfolioRepository)
	{
		$this->twig = $twig;
		$this->portfolioRepository = $portfolioRepository;
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function showPortfolio(Request $request, Response $response)
	{
		$userid = $_SESSION['user_id'];
		$portfolio = $this->portfolioRepository->getPortfolioTitle($userid);
		$isPortfolio = $this->portfolioRepository->checkIfPortfolioExists($userid);
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		//Get album names and cover
		$albums = $this->portfolioRepository->getAllUserAlbums($userid);
		return $this->twig->render($response, 'portfolio.twig', [
			'thereIsPortfolio' => $isPortfolio,
			'portfolioTitle' => $portfolio,
			'formAction' => $routeParser->urlFor('portfolio'),
			'albums' => $albums,
			'logged' => $_SESSION['logged']

		]);
	}

	public function createPortfolio(Request $request, Response $response)
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$data = $request->getParsedBody();
		if (isset($data['addAlbum'])) {
			$this->portfolioRepository->createAlbum($_SESSION['user_id'], "New Album");
			return $response->withHeader('Location', $routeParser->urlFor('portfolio'))->withStatus(200);
		}
		$userid = $_SESSION['user_id'];
		$portfolioTitle = $request->getParsedBody()['portfolioTitleValue'];
		$this->portfolioRepository->createPortfolio($userid, $portfolioTitle);

		return $response->withHeader('Location', $routeParser->urlFor('portfolio'))->withStatus(200);

	}

	public function showalbum(Request $request, Response $response)
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');


		$album = $this->portfolioRepository->getAlbumPhotosFromUser($id, $_SESSION['user_id']);
		return $this->twig->render($response, 'album.twig', [
			'formAction' => $routeParser->urlFor('uploadimage', ['id' => $id]),
			'photos' => $album,
			'logged' => $_SESSION['logged'],
			'albumId' => $id
		]);

	}

	public function deleteImage(Request $request, Response $response)
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');
		$data = $request->getParsedBody();
		$imageURL = $data['imageId'];

		if (isset($imageURL) && $imageURL != "") {
			$this->portfolioRepository->deletePhotoFromAlbum($id, $_SESSION['user_id'], $imageURL);
		} else {
			$this->portfolioRepository->deleteAlbum($id, $_SESSION['user_id']);
		}
		$album = $this->portfolioRepository->getAlbumPhotosFromUser($id, $_SESSION['user_id']);

		return $this->twig->render($response, 'album.twig', [
			'logged' => $_SESSION['logged'],
			'formAction' => $routeParser->urlFor('uploadimage', ['id' => $id]),
			'photos' => $album,
			'albumId' => $id
		]);

	}

	public function uploadImage(Request $request, Response $response)
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');
		$data = $request->getParsedBody();
		$imageURL = $data['url'];

		$image = $this->portfolioRepository->addPhotoToAlbum($id, $_SESSION['user_id'], $imageURL);
		$album = $this->portfolioRepository->getAlbumPhotosFromUser($id, $_SESSION['user_id']);
		return $this->twig->render($response, 'album.twig', [
			'logged' => $_SESSION['logged'],
			'formAction' => $routeParser->urlFor('uploadimage', ['id' => $id]),
			'photos' => $album,
			'albumId' => $id
		]);
	}

}