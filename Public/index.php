<?php

declare(strict_types=1);

use App\Response;
use App\Router\Router;
use FastRoute\Dispatcher;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__. '/../App/Views');
$twig = new Environment($loader);

$routeInfo = Router::dispatch();

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        echo $twig->render('404.twig');
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case Dispatcher::FOUND:
        [$className, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = (new $className())->{$method}($vars);

        /** @var Response $response */
        echo $twig->render($response->getViewName() . '.twig', $response->getData());
        break;
}