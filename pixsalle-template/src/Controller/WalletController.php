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
	private WalletRepository $walletRepository;

	/**
	 * @param Twig $twig
	 * @param WalletRepository $walletRepository
	 */
	public function __construct(Twig $twig, WalletRepository $walletRepository)
	{
		$this->twig = $twig;
		$this->walletRepository = $walletRepository;
	}

	public function showWallet(Request $request, Response $response): Response
	{
		return $this->twig->render($response, 'wallet.twig', [

		]);
	}

	/**
	 * @param Twig $twig
	 * @param WalletRepository $walletRepository
	 */
	public function showActualAmmountOfMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		$actualUser = $_SESSION['user'];
		$value = $this->walletRepository->getBalance($actualUser);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
				'walletValue' => $value,
			]
		);
	}

	public function addMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$moneyToAdd = $data['moneyToAdd'];

		$actualUser = $_SESSION['user'];
		$this->walletRepository->addMoney($actualUser, $moneyToAdd);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
				'walletValue' => $value,
			]
		);
	}

	public function removeMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$moneyToRemove = $data['moneyToRemove'];
		$actualUser = $_SESSION['user'];

		$this->walletRepository->removeMoney($actualUser, $moneyToRemove);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
			]
		);
	}

	public function addNewField(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$actualUser = $_SESSION['user'];

		$this->walletRepository->insertNewEntry($actualUser, 30);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
			]
		);
	}


}