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
}