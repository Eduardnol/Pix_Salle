<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\WalletRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class WalletController
{
	private Twig $twig;

	/**
	 * @param Twig $twig
	 * @param WalletRepository $walletRepository
	 */
	public function __construct(Twig $twig, WalletRepository $walletRepository)
	{
		$this->twig = $twig;
	}

	public function showActualAmmountOfMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		$value;

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
				'walletValue' => $value,
			]
		);
	}


}