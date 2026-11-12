<?php

namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Repository\HashtagRepository;

class AbonnerHashtagAction { 
    private HashtagRepository $hashtagRepository;

    public function __construct(HashtagRepository $hashtagRepository) { 
        $this->hashtagRepository = $hashtagRepository;
    }

    public function execute(): string { 
        if ($_SERVER["REQUEST_METHOD"] !== "POST") { 
            return "Methode HTTP non autorisée";
        }

        $utilisateurId = (int) ($_SESSION["utilisateur_id"] ?? 0);

        if ($utilisateurId <= 0) { 
            return "Aucun utilisateur selectionne";
        }

        $tag = $_POST["tag"] ?? "";

        if (!is_string($tag)) { 
            return "Hashtag invalide";
        }

        if ($tag === "" || strlen($tag) > 100 || !preg_match('/^[a-zA-Z0-9_]+$/', $tag)) { 
            return "hashtag invalide";
        }

        $hashtagId = $this->hashtagRepository->enregistrer($tag);
        $succes = $this->hashtagRepository->abonnerUtilisateur(
            $utilisateurId,
            $hashtagId
        );

        return $succes ? "Abonnement au hashtag enregistré avec succès" : "Impossible de s'abonner à ce hashtag";
    }
}