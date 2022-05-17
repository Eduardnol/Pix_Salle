<?php

declare(strict_types=1);


use Salle\PixSalle\Controller\API\BlogAPIController;
use Salle\PixSalle\Controller\ChangePasswordController;
use Salle\PixSalle\Controller\ExploreController;
use Salle\PixSalle\Controller\MembershipController;
use Salle\PixSalle\Controller\PortfolioController;
use Salle\PixSalle\Controller\ProfileController;
use Salle\PixSalle\Controller\SignUpController;
use Salle\PixSalle\Controller\UserSessionController;
use Salle\PixSalle\Controller\WalletController;
use Salle\PixSalle\Middleware\CheckSessionStartedMiddleware;
use Slim\App;

function addRoutes(App $app): void
{
	$app->get('/', UserSessionController::class . ':showSignInForm')->add(CheckSessionStartedMiddleware::class);;
	$app->get('/sign-in', UserSessionController::class . ':showSignInForm')->setName('signIn');
	$app->post('/sign-in', UserSessionController::class . ':signIn');
	$app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
	$app->post('/sign-up', SignUpController::class . ':signUp');
	$app->get('/explore', ExploreController::class . ':showImages')->add(CheckSessionStartedMiddleware::class);
	$app->get('/user/wallet', WalletController::class . ':showWallet')->add(CheckSessionStartedMiddleware::class)->setName('wallet');
	$app->post('/user/wallet', WalletController::class . ':addMoney');
	$app->get('/user/membership', MembershipController::class . ':showMembership')->add(CheckSessionStartedMiddleware::class)->setName('membership');
	$app->post('/user/membership', MembershipController::class . ':changeCurrentMembership');
	$app->get('/profile', ProfileController::class . ':showProfileForm')->add(CheckSessionStartedMiddleware::class)->setName('profile');
	$app->post('/profile', ProfileController::class . ':profile');
	$app->get('/profile/changePassword', ChangePasswordController::class . ':showPasswordForm')->add(CheckSessionStartedMiddleware::class)->setName('changePassword');
	$app->post('/profile/changePassword', ChangePasswordController::class . ':changePass');
	$app->get('/portfolio', PortfolioController::class . ':showPortfolio')->add(CheckSessionStartedMiddleware::class)->setName('portfolio');
	$app->post('/portfolio', PortfolioController::class . ':createPortfolio')->add(CheckSessionStartedMiddleware::class)->setName('portfolio');
	$app->get('/portfolio/album/{id}', PortfolioController::class.':createAlbum')->add(CheckSessionStartedMiddleware::class);
}
