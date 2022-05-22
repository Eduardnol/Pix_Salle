<?php

declare(strict_types=1);

use Salle\PixSalle\Controller\API\BlogAPIController;
use Salle\PixSalle\Controller\BlogController;
use Salle\PixSalle\Controller\ChangePasswordController;
use Salle\PixSalle\Controller\ExploreController;
use Salle\PixSalle\Controller\HomeController;
use Salle\PixSalle\Controller\MembershipController;
use Salle\PixSalle\Controller\PortfolioController;
use Salle\PixSalle\Controller\ProfileController;
use Salle\PixSalle\Controller\SignUpController;
use Salle\PixSalle\Controller\UserSessionController;
use Salle\PixSalle\Controller\WalletController;
use Salle\PixSalle\Middleware\CheckSessionStartedMiddleware;
use Slim\App;

//use Salle\PixSalle\Controller\API\BlogAPIController;

function addRoutes(App $app): void
{
	$app->get('/', HomeController::class . ':showHome')->setName('home');
    $app->get('/sign-in', UserSessionController::class . ':showSignInForm')->setName('signIn');
	$app->post('/sign-in', UserSessionController::class . ':signIn');
	$app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
	$app->post('/sign-up', SignUpController::class . ':signUp');
	$app->get('/explore', ExploreController::class . ':showImages')->add(CheckSessionStartedMiddleware::class)->setName('explore');
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
	$app->get('/portfolio/album/{id}', PortfolioController::class . ':showalbum')->add(CheckSessionStartedMiddleware::class)->setName('uploadimage');
	$app->post('/portfolio/album/{id}', PortfolioController::class . ':uploadImage')->add(CheckSessionStartedMiddleware::class)->setName('uploadimage');
	$app->delete('/portfolio/album/{id}', PortfolioController::class . ':deleteImage')->add(CheckSessionStartedMiddleware::class)->setName('uploadimage');
	$app->get('/api/blog', BlogController::class . ':showBlogForm')->setName('blog');
	$app->post('/api/blog', BlogController::class . ':postBlogForm');
	$app->get('/blog', BlogController::class . ':showBlogList')->setName('blog-list');
	$app->get('/api/blog/{id}', BlogController::class . ':showSpecificBlog')->setName('blogSpecific');
	$app->put('/api/blog/{id}', BlogController::class . ':updateSpecificBlog')->setName('blogSpecific');
	$app->delete('/api/blog/{id}', BlogController::class . ':deleteSpecificBlog')->setName('blogSpecific');

}
