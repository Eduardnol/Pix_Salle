<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\WalletRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class WalletController
{
	private Twig $twig;
	private WalletRepository $walletRepository;
	private ValidatorService $validator;

	/**
	 * @param Twig $twig
	 * @param WalletRepository $walletRepository
	 */
	public function __construct(Twig $twig, WalletRepository $walletRepository)
	{
		$this->twig = $twig;
		$this->walletRepository = $walletRepository;
		$this->validator = new ValidatorService();;
	}


	public function showWallet(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		return $this->twig->render($response, 'wallet.twig', [
			'wallet' => $this->showActualAmmountOfMoney(),
			'wallet_add' => $routeParser->urlFor('wallet'),
		]);
	}

	/**
	 * @param Twig $twig
	 * @param WalletRepository $walletRepository
	 */
	public function showActualAmmountOfMoney()
	{
		$actualUser = $_SESSION['user_id'];
		$result = $this->walletRepository->getBalance($actualUser);
		if ($result == null) {
			$this->addNewField();
			return 30;
		} else {
			return $result;
		}
	}

	public function addMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$moneyToAdd = $data['amount'];
		$validator = $this->validator->validateQuantity($moneyToAdd);

		if ($validator != '') {
			return $this->twig->render($response, 'wallet.twig', [
				'wallet' => $this->showActualAmmountOfMoney(),
				'wallet_add' => $routeParser->urlFor('wallet'),
				'errorAmmount' => $validator,
				'logged' => $_SESSION['logged']
			]);
		}


		$actualUser = $_SESSION['user_id'];
		$value = $this->walletRepository->addMoney($actualUser, (int)$moneyToAdd);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
				'wallet' => $value,
				'logged' => $_SESSION['logged']
			]
		);
	}

	public function removeMoney(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$moneyToRemove = $data['moneyToRemove'];
		$actualUser = $_SESSION['user_id'];

		$this->walletRepository->removeMoney($actualUser, $moneyToRemove);

		return $this->twig->render(
			$response,
			'wallet.twig',
			[
				'logged' => $_SESSION['logged']
			]
		);
	}

	public function addNewField()
	{
		$actualUser = $_SESSION['user_id'];
		$this->walletRepository->insertNewEntry($actualUser, 30);

	}


}