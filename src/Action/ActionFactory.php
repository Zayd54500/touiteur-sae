<?php

namespace Zayd54500\TouiteurSae\Action;

use Zayd54500\TouiteurSae\Repository\HashtagRepository;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;
use Zayd54500\TouiteurSae\Repository\UtilisateurRepository;
use Zayd54500\TouiteurSae\Service\HashtagExtractor;

class ActionFactory
{
    public function create(string $nomAction): object
    {
        return match ($nomAction) {
            'detail' => new DetailTouiteAction(),
            'auteur' => new TouitesAuteurAction(),
            'tag' => new TouitesTagAction(),
            'publication' => new PublicationTouiteAction(new TouiteRepository(), new HashtagExtractor(), new HashtagRepository()),
            'classement' => new ClassementUtilisateursAction(new UtilisateurRepository()),
            'suivre' => new SuivreUtilisateurAction(new UtilisateurRepository()),
            'abonner-tag' => new AbonnerHashtagAction(new HashtagRepository()),
            default => new ListeTouitesAction(),
        };
    }
}