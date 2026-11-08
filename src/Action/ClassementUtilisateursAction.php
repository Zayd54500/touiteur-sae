<?php

namespace Zayd54500\TouiteurSae\Action;

use Zayd54500\TouiteurSae\Repository\UtilisateurRepository;

class ClassementUtilisateursAction
{
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public function execute(): string
    {
        $utilisateurs = $this->utilisateurRepository->obtenirClassement();

        $html = '<h2>Utilisateurs les plus suivis</h2>';
        $html .= '<ol>';

        foreach ($utilisateurs as $utilisateur) {
            $pseudo = htmlspecialchars(
                $utilisateur['pseudo'],
                ENT_QUOTES,
                'UTF-8'
            );

            $nombreAbonnes = (int) $utilisateur['nombre_abonnes'];

            $html .= '<li>'
                . $pseudo
                . ' - '
                . $nombreAbonnes
                . ' abonné(s)'
                . '</li>';
        }

        $html .= '</ol>';

        return $html;
    }
}