<?php

namespace Salle\PixSalle\Controller;

use Slim\Views\Twig;

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

	public function showPortfolio(Request $request, Response $response)
	{
		$portfolio = $this->portfolioRepository->getPortfolio();

		return $this->twig->render($response, 'portfolio.twig');
	}


}