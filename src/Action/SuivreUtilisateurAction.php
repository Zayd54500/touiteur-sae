<?php
namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Repository\UtilisateurRepository;

class SuivreUtilisateurAction {
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository) { 
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public function execute(): string { 
        if ($_SERVER['REQUEST_METHOD'] !== "POST") { 
            return "Methode HTTP non autorisée";
        }

        $suiviId = filter_var($_POST['suivi_id'] ?? null, FILTER_VALIDATE_INT);
        if ($suiviId === false || $suiviId < 1) { 
            return "Identifiant utilisateur invalide";
        }

        $suiveurId = filter_var($_SESSION['utilisateur_id'] ?? null, FILTER_VALIDATE_INT);
        if($suiveurId === false || $suiveurId < 1 ) { 
            return "Aucun utilisateur selectionné";
        }

        $succes = $this->utilisateurRepository->suivreUtilisateur(
            $suiveurId,
            $suiviId
        );

        if ($succes === true) { 
            return "Utilisateur suivi avec succès";
        } else { 
            return "Impossible de suivre cet utilisateur";
        }

    }
}
?>