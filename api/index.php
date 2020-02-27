<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

    $app = new \Slim\App;

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });


    //routes
    require '../src/routes/courses.php';
        try {
            $app->run();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
