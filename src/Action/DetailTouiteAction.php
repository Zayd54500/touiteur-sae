<?php
namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;


class DetailTouiteAction { 
    public function execute() : string { 
        $id = $_GET["id"] ?? 0;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) { 
            return "Identifiant du touite invalide...";
        }
        $repository = new TouiteRepository();
        $touite = $repository->getById($id);
        if ($touite === null) { 
            return "Touite introuvable";
        }
        return "<h2>" . htmlspecialchars($touite["pseudo"], ENT_QUOTES, "UTF-8") . "</h2>" . 
        "<p>" . htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") . "</p>" . 
        "<small> Publié le " . htmlspecialchars($touite["date_publication"], ENT_QUOTES, "UTF-8") . "</small>" . 
        "<br>" . "<a href='?action=liste'>" . "<-- Retour au fil d'actualité" . "</a>";
    }

}
?>