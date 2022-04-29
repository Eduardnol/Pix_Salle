<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Salle\PixSalle\Repository\MembershipRepository;
use Salle\PixSalle\Service\ValidatorService;
use Slim\Views\Twig;

class MembershipController implements MembershipRepository
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

	public function showCurrentMembership(int $userId)
	{
		// TODO: Implement showCurrentMembership() method.
	}

	public function changeCurrentMembership(int $userId)
	{
		// TODO: Implement changeCurrentMembership() method.
	}
}