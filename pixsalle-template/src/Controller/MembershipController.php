<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\MembershipRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class MembershipController
{
	private Twig $twig;
	private ValidatorService $validator;
	private MembershipRepository $membershipRepository;

	public function __construct(
		Twig                 $twig,
		MembershipRepository $membershipRepository
	)
	{
		$this->twig = $twig;
		$this->membershipRepository = $membershipRepository;
		$this->validator = new ValidatorService();
	}

	public function showMembership(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();

		return $this->twig->render(
			$response,
			'membership.twig',
			[
				'formAction' => $routeParser->urlFor('membership')
			]
		);
	}

	public function showActualMembership()

	{
		$id = $_SESSION['user_id'];
		return $this->membershipRepository->showCurrentMembership($id);

	}

	public function changeCurrentMembership(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$isActive = $data['isActive'];
		$userId = $_SESSION['user_id'];
		$this->membershipRepository->changeCurrentMembership($userId, $isActive);

		//TODO: change the twig page
		return $this->twig->render(
			$response,
			'membership.twig',
			[
				'formErrors' => $errors,
				'formData' => $data,
				'formAction' => $routeParser->urlFor('signUp')
			]
		);
	}


}