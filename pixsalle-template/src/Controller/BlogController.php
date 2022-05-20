<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\BlogRepository;
use Slim\Views\Twig;

class BlogController
{
	private Twig $twig;
	private BlogRepository $blogRepository;

	/**
	 * @param Twig $twig
	 * @param BlogRepository $blogRepository
	 */
	public function __construct(
		Twig           $twig,
		BlogRepository $blogRepository
	)
	{
		$this->twig = $twig;
		$this->blogRepository = $blogRepository;
	}

	public function showBlogForm(Request $request, ResponseInterface $response): Response
	{

		$blogs = $this->blogRepository->showBlogs();
		$response->withHeader('Content-type', 'application/json');

		$response->getBody()->write(json_encode($blogs, JSON_THROW_ON_ERROR));
		return $response;


	}

	public function showBlogList(Request $request, Response $response): Response
	{

		$blogs = $this->blogRepository->showBlogs();

		return $this->twig->render(
			$response,
			'blog-list.twig',
			[
				'userId' => $_SESSION['user_id'],
				'logged' => $_SESSION['logged'],
				'blogs' => $blogs
			]
		);
	}

	public function postBlogForm(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$this->blogRepository->createBlog($data['title'], $data['content'], $data['userId']);
		$blogs = $this->blogRepository->showBlogs();
		return $this->twig->render(
			$response->withHeader('Location', '/blog')->withStatus(302),
			'blog-list.twig',
			[
				'userId' => $_SESSION['user_id'],
				'logged' => $_SESSION['logged'],
				'blogs' => $blogs
			]
		);
	}

}