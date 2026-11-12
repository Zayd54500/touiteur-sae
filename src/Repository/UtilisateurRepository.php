<?php

namespace Zayd54500\TouiteurSae\Repository;

use PDO;
use Zayd54500\TouiteurSae\Database\DatabaseConnection;

class UtilisateurRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getConnection();
    }

    public function obtenirClassement(): array
    {
        $requete = $this->pdo->query(
            'SELECT u.id, u.pseudo, COUNT(s.suivi_id) AS nombre_abonnes
             FROM utilisateur AS u
             LEFT JOIN suivi AS s
                ON s.suivi_id = u.id
             GROUP BY u.id, u.pseudo
             ORDER BY nombre_abonnes DESC, u.pseudo ASC'
        );

        return $requete->fetchAll();
    }

    public function suivreUtilisateur(int $suiveurId, int $suiviId): bool { 
        if ($suiveurId === $suiviId) { 
            return false;
        }

        $requete = $this->pdo->prepare(
            'SELECT count(*) FROM suivi WHERE suiveur_id = :suiveur_id AND suivi_id = :suivi_id'
        );

        $requete->execute([
            "suiveur_id" => $suiveurId,
            "suivi_id" => $suiviId
        ]);

        $nombre = (int) $requete->fetchColumn();

        if ($nombre > 0) { 
            return false;
        }

        $requete = $this->pdo->prepare(
            'INSERT INTO suivi (suiveur_id, suivi_id) VALUES (:suiveur_id, :suivi_id)'
        );
        return $requete->execute([
            "suiveur_id" => $suiveurId,
            "suivi_id" => $suiviId
        ]);
    }

    public function obtenirUtilisateurs(): array { 
        $requete = $this->pdo->query(
            'SELECT id, pseudo FROM utilisateur ORDER BY pseudo ASC'
        ); 

        return $requete->fetchAll();
    }

    public function obtenirUtilisateurParPseudo(string $pseudo): array|false { 
        $requete = $this->pdo->prepare(
            'SELECT id, pseudo FROM utilisateur WHERE pseudo = :pseudo'
        );

        $requete->execute([
            "pseudo" => $pseudo
        ]);

        return $requete->fetch();
    }

    public function estDejaSuivi(int $suiveurId, int $suiviId): bool {
        if ($suiveurId === $suiviId) { 
            return false;
        }
 
        $requete = $this->pdo->prepare(
            'SELECT count(*) FROM suivi WHERE suiveur_id = :suiveur_id AND suivi_id = :suivi_id'
        );

        $requete->execute([
            "suiveur_id" => $suiveurId,
            "suivi_id" => $suiviId
        ]);

        return (int) $requete->fetchColumn() > 0;
    }
}