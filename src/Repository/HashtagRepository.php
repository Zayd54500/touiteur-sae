<?php

namespace Zayd54500\TouiteurSae\Repository;

use InvalidArgumentException;
use PDO;
use Zayd54500\TouiteurSae\Database\DatabaseConnection;

class HashtagRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getConnection();
    }

    public function enregistrer(string $nom): int
    {
        $nom = trim($nom);

        if ($nom === '') {
            throw new InvalidArgumentException(
                'Le nom du hashtag ne peut pas être vide.'
            );
        }

        $requete = $this->pdo->prepare(
            'SELECT id FROM hashtag WHERE nom = :nom'
        );

        $requete->execute([
            'nom' => $nom
        ]);

        $hashtagId = $requete->fetchColumn();

        if ($hashtagId !== false) {
            return (int) $hashtagId;
        }

        $requete = $this->pdo->prepare(
            'INSERT INTO hashtag (nom) VALUES (:nom)'
        );

        $requete->execute([
            'nom' => $nom
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function associer(int $touiteId, int $hashtagId): void
    {
        if ($touiteId <= 0 || $hashtagId <= 0) {
            throw new InvalidArgumentException(
                'Les identifiants du touite et du hashtag sont invalides.'
            );
        }

        $requete = $this->pdo->prepare(
            'INSERT INTO touite_hashtag (touite_id, hashtag_id)
             VALUES (:touite_id, :hashtag_id)
             ON DUPLICATE KEY UPDATE touite_id = touite_id' //Si cette association existe déjà, ne provoque pas d’erreur et ne change rien.
        );

        $requete->execute([
            'touite_id' => $touiteId,
            'hashtag_id' => $hashtagId
        ]);
    }

    public function abonnerUtilisateur(int $utilisateurId, int $hashtagId): bool
    {
        if ($utilisateurId <= 0 || $hashtagId <= 0) {
            return false;
        }

        $requete = $this->pdo->prepare(
            'INSERT INTO utilisateur_hashtag (utilisateur_id, hashtag_id)
            VALUES (:utilisateur_id, :hashtag_id)
            ON DUPLICATE KEY UPDATE hashtag_id = hashtag_id'
        );

        return $requete->execute([
            'utilisateur_id' => $utilisateurId,
            "hashtag_id" => $hashtagId
        ]);
    }
}
