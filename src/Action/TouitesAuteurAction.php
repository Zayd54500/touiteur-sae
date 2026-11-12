<?php

namespace Zayd54500\TouiteurSae\Action;

use Zayd54500\TouiteurSae\Repository\TouiteRepository;
use Zayd54500\TouiteurSae\Repository\UtilisateurRepository;

class TouitesAuteurAction
{
    public function execute(): string
    {
        $pseudo = $_GET["pseudo"] ?? "";

        if (!is_string($pseudo)) {
            return "Pseudo invalide";
        } else {
            $pseudo = trim($pseudo);
        }

        if ($pseudo === "") {
            return "Pseudo invalide";
        }

        $utilisateurRepository = new UtilisateurRepository();
        $auteur = $utilisateurRepository->obtenirUtilisateurParPseudo($pseudo);

        if ($auteur === false) {
            return "Utilisateur introuvable";
        }

        $auteurId = (int) $auteur["id"];
        $utilisateurCourantId = (int) ($_SESSION["utilisateur_id"] ?? 0);



        $repository = new TouiteRepository();
        $touites = $repository->getByAuteur($pseudo);
        

        usort($touites, function ($a, $b) {
            return strcmp(
                $b["date_publication"],
                $a["date_publication"]
            );
        });

        $html = "<h2>" . "Touites de " . htmlspecialchars($pseudo, ENT_QUOTES, "UTF-8") . "</h2>";

        if ($utilisateurCourantId > 0 && $utilisateurCourantId !== $auteurId) {
            $dejaSuivi = $utilisateurRepository->estDejaSuivi(
                $utilisateurCourantId,
                $auteurId
            );

            if ($dejaSuivi === true) {
                $html .= "<span class='badge bg-secondary'>Déjà suivi</span>";
            } else {
                $html .= "
                <form method='POST' action='?action=suivre'>
                    <input type='hidden' name='suivi_id' value='{$auteurId}'>
                    <button type='submit' class='btn btn-primary'>
                        Suivre
                    </button>
                </form>
            ";
            }
        }

        if (empty($touites)) {
            $html .= "<p class='text-secondary'>Cet utilisateur n'a encore publié aucun touite</p>";
        }

        foreach ($touites as $touite) {
            $html .= "<p>" . htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") . "</p>" .
                "<small>" . htmlspecialchars($touite["date_publication"]) . "</small>";
        }
        return $html;
    }
}
