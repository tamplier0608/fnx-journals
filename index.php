<?php

function d($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

phpversion() > '5.4.*' || die('Application requires PHP version 5.4 or higher');

defined('ENV') || define('ENV', 'dev');
defined('DOCUMENT_ROOT') || define('DOCUMENT_ROOT', __DIR__);
$_SERVER['DOCUMENT_ROOT'] = __DIR__;

if (ENV == 'dev' || (isset($_GET['debug']) && $_GET['debug'] == 'very_secret_param')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

# autoloader configuration
require_once __DIR__ . '/vendor/autoload.php';

$request = Core\Request::createFromGlobals();
$appConfig = new Core\Config(__DIR__ . '/app/configs/application.' . ENV . '.php');

try {
    $app = new Core\Application($appConfig, ENV);
    $app->bootstrap();
    $response = $app->handle($request);
    $response->send();
} catch (Core\HttpException $e) {
    die('<h1>' . $e->getMessage() . '</h1>');
}
