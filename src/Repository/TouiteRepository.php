<?php
namespace Zayd54500\TouiteurSae\Repository;

class TouiteRepository { 
    public function getAll() :array { 
        $touites = [ 
            [
                "id" => 3,
                "pseudo" => "Zayd",
                "contenu" => "Je suis le meilleur touitos de ce réseau", 
                "date_publication" => "01/11/2026"
            ],
            [
                "id" => 1,
                "pseudo" => "Vincent",
                "contenu" => "On test ce réseau de merde pour voir ^^",
                "date_publication" => "03/11/2026"
            ], 
            [
                "id" => 2,
                "pseudo" => "Aminou",
                "contenu" => "Je pense que vincent est une merde",
                "date_publication" => "02/11/2026"
            ], 
            [
                "id" => 4,
                "pseudo" => "Vincent",
                "contenu" => "Mon deuxième touite sur ce réseaux d'enculé",
                "date_publication" => "03/11/2026"     
            ]
        ];
        return $touites;
    }

    public function getById(int $id): ?array{ 
        $touites = $this->getAll();
        foreach($touites as $touite) { 
            if ($touite["id"] === $id) { 
                return $touite;
            }
        }
        return null;
    }

    public function getByAuteur(string $pseudo): array {
        $touites = $this->getAll();
        $resultats = [];
        foreach($touites as $touite) { 
            if ($touite["pseudo"] === $pseudo) { 
                $resultats[] = $touite;
            }
        }
        return $resultats;
    }
}
?>