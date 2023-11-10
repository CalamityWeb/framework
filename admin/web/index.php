<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use tframe\admin\controllers\AuthController;
use tframe\admin\controllers\RoutesManagement;
use tframe\admin\controllers\SiteController;
use tframe\core\Application;

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../common/config');
$dotenv->load();

$config = [
    'database' => [
        'host' => $_ENV['DATABASE_HOST'],
        'dbname' => $_ENV['DATABASE_DBNAME'],
        'username' => $_ENV['DATABASE_USERNAME'],
        'password' => $_ENV['DATABASE_PASSWORD']
    ],
    'mailer' => [
        'system_address' => $_ENV['SYSTEM_EMAIL'],
        'host' => $_ENV['EMAIL_HOST'],
        'username' => $_ENV['EMAIL_USERNAME'],
        'password' => $_ENV['EMAIL_PASSWORD']
    ],
    'maintenance' => $_ENV['ADMIN_MAINTENANCE'],
    'language' => $_ENV['ADMIN_LANGUAGE']
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'index']);

/* * Authentication routes  */
// Login
$app->router->getNpost('/auth/login', [AuthController::class, 'login']);
//Register
$app->router->getNpost('/auth/register', [AuthController::class, 'register']);
// Logout
$app->router->get('/auth/logout', [AuthController::class, 'logout']);
// Forgot password
$app->router->getNpost('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
// Reset password
$app->router->getNpost('/auth/reset-password/{token}', [AuthController::class, 'resetPassword']);

/* * Routes Management */
$app->router->get('/routes-management/index', [RoutesManagement::class, 'index']);

$app->run();