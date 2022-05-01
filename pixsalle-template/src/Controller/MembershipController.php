<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\MembershipRepository;
use Salle\PixSalle\Repository\WalletRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class MembershipController
{
	private Twig $twig;
	private ValidatorService $validator;
	private MembershipRepository $membershipRepository;
	private WalletRepository $walletRepository;

	public function __construct(
		Twig                 $twig,
		MembershipRepository $membershipRepository,
		WalletRepository     $walletRepository
	)
	{
		$this->twig = $twig;
		$this->membershipRepository = $membershipRepository;
		$this->validator = new ValidatorService();
		$this->walletRepository = $walletRepository;
	}

	public
	function showMembership(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$membership = $this->showActualMembership();
		if ($membership === null) {
			$this->newMembership();
			$membership = $this->showActualMembership();
		}

		return $this->twig->render(
			$response,
			'membership.twig',
			[
                'logged' => $_SESSION['logged'],
                'formAction' => $routeParser->urlFor('membership'),
                'actualMembership' => $membership,
            ]
		);
	}

	public
	function showActualMembership()

	{
		$id = $_SESSION['user_id'];
		return $this->membershipRepository->showCurrentMembership($id);
	}

	public
	function changeCurrentMembership(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$isActiveBool = 0;
		if (isset($data['activate'])) {
			$isActiveBool = 1;
			$id = $_SESSION['user_id'];
			//Check if the user has enough money to activate the membership
            /*
            $user = $this->walletRepository->getBalance($id);
            $membership = $this->showActualMembership();
            if ($user - 2 < 0) {
                return $this->twig->render(
                    $response,
                    'membership.twig',
                    [
            'logged' => $_SESSION['logged'],
                        'error' => 'You do not have enough money to activate the membership',
                        'actualMembership' => $membership,
                        'formAction' => $routeParser->urlFor('membership')
                    ]
                );
            } else {
                $this->walletRepository->removeMoney($id, 2);
            }*/
		}
		if (isset($data['deactivate'])) {
			$isActiveBool = 0;
		}
		$userId = $_SESSION['user_id'];
		$this->membershipRepository->changeCurrentMembership($userId, $isActiveBool);
		$membership = $this->showActualMembership();
		//TODO: change the twig page
		return $this->twig->render(
			$response,
			'membership.twig',
            [
                'logged' => $_SESSION['logged'],
                'actualMembership' => $membership,
                'formAction' => $routeParser->urlFor('membership')
            ]
		);
	}

	private
	function newMembership()
	{
		$id = $_SESSION['user_id'];
		$this->membershipRepository->insertNewMembership($id);

	}


}