<?php

namespace Zayd54500\TouiteurSae\Action;

use InvalidArgumentException;
use Zayd54500\TouiteurSae\Classes\Database;
use Zayd54500\TouiteurSae\Classes\Utilisateur;
use Zayd54500\TouiteurSae\Repository\HashtagRepository;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;
use Zayd54500\TouiteurSae\Service\HashtagExtractor;

class ActionFactory
{
    public function create(string $nomAction): object
    {
        $db = new Database(new TouiteRepository());
        $utilisateurs = $db->__get('listeUtilisateur');
        $premierUtilisateur = reset($utilisateurs) ?: new Utilisateur("visiteur");
        return match ($nomAction) {
            'detail' => new DetailTouiteAction(),
            'auteur' => new TouitesAuteurAction(),
            'tag' => new TouitesTagAction(),
            'publication' => new PublicationTouiteAction(new TouiteRepository(), new HashtagExtractor(), new HashtagRepository()),
            default => new ListeTouitesAction($db, $premierUtilisateur),
        };
    }
}