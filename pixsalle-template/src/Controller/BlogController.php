<?php

declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\BlogRepository;
use Slim\Routing\RouteContext;
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

		$response->getBody()->write(json_encode($blogs));
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
		$blogs = $this->blogRepository->createBlog($data['title'], $data['content'], $data['userId']);
		//$blogs = $this->blogRepository->showBlogs();
		$response->withHeader('Content-type', 'application/json');

		$response->getBody()->write(json_encode($blogs));
		return $response;
//		return $this->twig->render(
//			$response->withHeader('Location', '/blog')->withStatus(302),
//			'blog-list.twig',
//			[
//				'userId' => $_SESSION['user_id'],
//				'logged' => $_SESSION['logged'],
//				'blogs' => $blogs
//			]
//		);
	}

	public function showSpecificBlog(Request $request, Response $response): Response
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');

		$blogs = $this->blogRepository->showSpecificBlog((int)$id);

		$response->withHeader('Content-type', 'application/json');

		$response->getBody()->write(json_encode($blogs));
		return $response;

	}

	public function updateSpecificBlog(Request $request, Response $response): Response
	{
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');
		$data = $request->getParsedBody();
		$blogs = $this->blogRepository->updateSpecificBlog((int)$id, $data['title'], $data['content'], (int)1);

		$response->withHeader('Content-type', 'application/json');
		$response->getBody()->write(json_encode($blogs));
		return $response;

	}

	public function deleteSpecificBlog(Request $request, Response $response): Response
	{
		$request->getUri()->getPath();
		$id = $request->getAttribute('id');
		$data = $request->getParsedBody();

		$blogs = $this->blogRepository->deleteSpecificBlog((int)$id, (int)1);

		$response->withHeader('Content-type', 'application/json');
		if ($blogs) {
			$responseMessage = array("message" => "Blog entry with id ${$id} was successfully deleted");
			$response->getBody()->write(json_encode($responseMessage));
		}
		return $response;

	}

}