<?php

declare(strict_types=1);

use Salle\PixSalle\Controller\API\BlogAPIController;
use Salle\PixSalle\Controller\MembershipController;
use Salle\PixSalle\Controller\ExploreController;
use Salle\PixSalle\Controller\ChangePasswordController;
use Salle\PixSalle\Controller\ProfileController;
use Salle\PixSalle\Controller\SignUpController;
use Salle\PixSalle\Controller\UserSessionController;
use Salle\PixSalle\Controller\WalletController;
use Slim\App;

function addRoutes(App $app): void
{
    $app->get('/', UserSessionController::class . ':showSignInForm');
    $app->get('/sign-in', UserSessionController::class . ':showSignInForm')->setName('signIn');
    $app->post('/sign-in', UserSessionController::class . ':signIn');
    $app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
    $app->post('/sign-up', SignUpController::class . ':signUp');
    $app->get('/explore', ExploreController::class . ':showImages');
	$app->get('/user/wallet', WalletController::class . ':showWallet')->setName('wallet');
	$app->post('/user/wallet', WalletController::class . ':addMoney');
	$app->get('/user/membership', MembershipController::class . ':showMembership')->setName('membership');
	$app->post('/user/membership', MembershipController::class . ':changeCurrentMembership');
    $app->get('/profile', ProfileController::class . ':showProfileForm')->setName('profile');
    $app->post('/profile', ProfileController::class . ':profile');
    $app->get('/profile/changePassword', ChangePasswordController::class . ':showPasswordForm')->setName('changePassword');
    $app->post('/profile/changePassword', ChangePasswordController::class . ':changePass');
}
