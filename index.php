<?php


require_once __DIR__.'/conf/config.php';
require_once __DIR__.'/vendor/autoload.php';

use App\Router\Router;

$router = new Router();
$router->resolve();


?>