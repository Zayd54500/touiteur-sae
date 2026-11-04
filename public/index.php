<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Zayd54500\TouiteurSae\Action\ListeTouitesAction;
use Zayd54500\TouiteurSae\Action\DetailTouiteAction;
use Zayd54500\TouiteurSae\Action\PublicationTouiteAction;
use Zayd54500\TouiteurSae\Action\TouitesAuteurAction;
use Zayd54500\TouiteurSae\Action\TouitesTagAction;
use Zayd54500\TouiteurSae\Repository\HashtagRepository;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;
use Zayd54500\TouiteurSae\Service\HashtagExtractor;

$action = $_GET["action"] ?? "liste";

if ($action === "liste") { 
    $actionObjet = new ListeTouitesAction();
} else if ($action === "detail") { 
    $actionObjet = new DetailTouiteAction();
} else if ($action === "auteur") { 
    $actionObjet = new TouitesAuteurAction();
} else if ($action === "tag") { 
    $actionObjet = new TouitesTagAction();
} else if ($action === "publication") {
    $repository = new TouiteRepository();
    $hashtagExtractor = new HashtagExtractor();
    $hashtagRepository = new HashtagRepository();
    $actionObjet = new PublicationTouiteAction($repository, $hashtagExtractor, $hashtagRepository);
} else { 
    echo "Action inconnue...";
}

if (isset($actionObjet)) { 
    echo $actionObjet->execute();
}

?>