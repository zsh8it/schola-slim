<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();


$app->addBodyParsingMiddleware();
// ============================================
// LOAD ROUTES
// ============================================
require __DIR__ . '/../belajar/fase-1/1.1-routing-dasar/get.php';
require __DIR__ . '/../belajar/fase-1/1.1-routing-dasar/post.php';
require __DIR__ . '/../belajar/fase-1/1.1-routing-dasar/put.php';
require __DIR__ . '/../belajar/fase-1/1.1-routing-dasar/patch.php';
require __DIR__ . '/../belajar/fase-1/1.1-routing-dasar/delete.php';








$app->run();
















// $app->get('/', function (Request $request, Response $response) {
//     $response->getBody()->write("Hello Slim di Laragon!");
//     return $response;
// });
