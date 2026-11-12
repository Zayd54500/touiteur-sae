<?php

namespace Zayd54500\TouiteurSae\Repository;

use Zayd54500\TouiteurSae\Database\DatabaseConnection;

class TouiteRepository {
    private array $touitesInitiaux = [
        [
            "id" => 3,
            "pseudo" => "Zayd",
            "contenu" => "Je suis le meilleur touitos de ce réseau #Prouveur",
            "date_publication" => "01/11/2026",
            "hastags" => "Prouveur"
        ],
        [
            "id" => 1,
            "pseudo" => "Vincent",
            "contenu" => "On test ce réseau de merde pour voir ^^ #Hater",
            "date_publication" => "03/11/2026",
            "hastags" => "Hater"
        ],
        [
            "id" => 2,
            "pseudo" => "Aminou",
            "contenu" => "Je pense que vincent est une merde #Hater",
            "date_publication" => "02/11/2026",
            "hastags" => "Hater"
        ],
        [
            "id" => 4,
            "pseudo" => "Vincent",
            "contenu" => "Mon deuxième touite sur ce réseaux d'enculé #Heureux",
            "date_publication" => "03/11/2026",
            "hastags" => "Heureux"
        ]
    ];

    public function getAll(): array
    {
        $pdo = DatabaseConnection::getConnection();
        $sql = "
            SELECT t.id, t.contenu, t.date_publication, u.pseudo
            FROM touite as t
            INNER JOIN utilisateur AS u
                ON t.utilisateur_id = u.id
            ORDER BY t.date_publication DESC
            ";
        $requete = $pdo->query($sql);
        return $requete->fetchAll();
    }

    public function enregistrer(string $contenu, int $utilisateurId): int
    {
        $pdo = DatabaseConnection::getConnection();
        $sql = "
        INSERT INTO touite (contenu, utilisateur_id)
        VALUES (:contenu, :utilisateur_id)
        ";

        $requete = $pdo->prepare($sql);
        $requete->execute([
            'contenu' => $contenu,
            'utilisateur_id' => $utilisateurId
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function getById(int $id): ?array
    {
        $pdo = DatabaseConnection::getConnection();
        $sql = "
        SELECT t.id, t.contenu, t.date_publication, u.pseudo
        FROM touite as t
        INNER JOIN utilisateur AS u
            ON t.utilisateur_id = u.id
        WHERE t.id = :id
        ";

        $requete = $pdo->prepare($sql);
        $requete->execute(['id' => $id]);
        $touite = $requete->fetch();
        return $touite ?: null;
    }

    public function getByAuteur(string $pseudo): array
    {
        $pdo = DatabaseConnection::getConnection();
        $sql = "
        SELECT t.id, t.contenu, t.date_publication, u.pseudo
        FROM touite as t
        INNER JOIN utilisateur AS u
            ON t.utilisateur_id = u.id
        WHERE u.pseudo = :pseudo
        ORDER BY t.date_publication DESC
        ";

        $requete = $pdo->prepare($sql);
        $requete->execute(['pseudo' => $pseudo]);

        return $requete->fetchAll();
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

    public function getMurUtilisateur(int $utilisateurId): array { 
        $pdo = DatabaseConnection::getConnection();
        $sql = "
            SELECT t.id, t.contenu, t.date_publication, u.pseudo
            FROM touite as t
            INNER JOIN utilisateur AS u ON t.utilisateur_id = u.id
            INNER JOIN suivi AS s ON s.suivi_id = u.id
            WHERE s.suiveur_id = :utilisateur_id
            ORDER BY t.date_publication DESC, t.id DESC
            LIMIT 20
            ";
        $requete = $pdo->prepare($sql);
        $requete->execute([
            "utilisateur_id" => $utilisateurId
        ]);

        return $requete->fetchAll();
    }
}