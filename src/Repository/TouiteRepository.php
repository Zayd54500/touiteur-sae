<?php

namespace Zayd54500\TouiteurSae\Repository;

class TouiteRepository {
    private array $touitesInitiaux = [
        [
            "id" => 3,
            "pseudo" => "Zayd",
            "contenu" => "Je suis le meilleur touitos de ce réseau #Prouveur",
            "date_publication" => "01/11/2026"
        ],
        [
            "id" => 1,
            "pseudo" => "Vincent",
            "contenu" => "On test ce réseau de merde pour voir ^^ #Hater",
            "date_publication" => "03/11/2026"
        ],
        [
            "id" => 2,
            "pseudo" => "Aminou",
            "contenu" => "Je pense que vincent est une merde #Hater",
            "date_publication" => "02/11/2026"
        ],
        [
            "id" => 4,
            "pseudo" => "Vincent",
            "contenu" => "Mon deuxième touite sur ce réseaux d'enculé #Heureux",
            "date_publication" => "03/11/2026"
        ]
    ];

    public function __construct()
    {
        if (!isset($_SESSION['touites'])) {
            $_SESSION['touites'] = $this->touitesInitiaux;
        }
    }
    public function getAll(): array
    {
        return $_SESSION['touites'];
    }

    public function enregistrer(string $contenu, int $utilisateurId): int
    {
        $nouvelId = count($_SESSION['touites']) + 1;

        $_SESSION['touites'][] = [
            "id" => $nouvelId,
            "pseudo" => "utilisateur_test",
            "contenu" => $contenu,
            "date_publication" => date("d/m/Y")
        ];

        return $nouvelId;
    }

    public function getById(int $id): ?array
    {
        foreach ($_SESSION['touites'] as $touite) {
            if ($touite["id"] === $id) {
                return $touite;
            }
        }

        return null;
    }

    public function getByAuteur(string $pseudo): array
    {
        $resultats = [];

        foreach ($_SESSION['touites'] as $touite) {
            if ($touite["pseudo"] === $pseudo) {
                $resultats[] = $touite;
            }
        }

        return $resultats;
    }

    public function getByTag(string $tag): array { 
        $resultats = [];
        $touites = $this->getAll();

        foreach ($touites as $touite) { 
            if (preg_match('/#' . preg_quote($tag, '/') . '\b/i' , $touite["contenu"]) === 1) { 
                $resultats[] = $touite;
            }
        }
        return $resultats;
    }
}