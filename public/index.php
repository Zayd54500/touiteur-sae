<?php

use Zayd54500\TouiteurSae\Action\ActionFactory;
use Zayd54500\TouiteurSae\Dispatcher\Dispatcher;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';



$action = $_GET["action"] ?? "liste";

$factory = new ActionFactory();
$dispatcher = new Dispatcher($factory);
echo $dispatcher->run($action);

?>