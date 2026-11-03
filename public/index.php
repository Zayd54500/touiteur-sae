<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Zayd54500\TouiteurSae\Action\ListeTouitesAction;
$action = new ListeTouitesAction();
echo $action->execute();
?>