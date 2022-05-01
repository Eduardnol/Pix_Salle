<?php
declare(strict_types=1);

namespace Salle\PixSalle\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

final class CheckSessionStartedMiddleware
{
	public function __invoke(Request $request, RequestHandler $handler)
	{
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
			$response = new Response();
			return $response->withStatus(200)->withHeader('Location', $routeParser->urlFor("signIn"));
		} else {
			return $handler->handle($request);
		}
	}
}