<?php

namespace Zayd54500\TouiteurSae\Action;

use InvalidArgumentException;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class PublicationTouiteAction
{
    private TouiteRepository $touiteRepository;

    public function __construct(TouiteRepository $touiteRepository)
    {
        $this->touiteRepository = $touiteRepository;
    }

    public function publier(string $contenu, int $utilisateurId): int
    {
        $contenu = trim($contenu);

        if ($contenu === '') {
            throw new InvalidArgumentException(
                'Le contenu du touite ne peut pas être vide.'
            );
        }

        if (mb_strlen($contenu) > 235) {
            throw new InvalidArgumentException(
                'Le touite ne peut pas dépasser 235 caractères.'
            );
        }

        if ($utilisateurId <= 0) {
            throw new InvalidArgumentException(
                'L’identifiant de l’utilisateur est invalide.'
            );
        }

        return $this->touiteRepository->enregistrer(
            $contenu,
            $utilisateurId
        );
    }
    public function execute(): string
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = $_POST['contenu'] ?? '';

            if (!is_string($contenu)) {
                $message = 'Le contenu envoyé est invalide.';
            } else {
                try {
                    $id = $this->publier($contenu, 1);
                    $message = 'Touite publié avec succès. Identifiant : ' . $id;
                } catch (InvalidArgumentException $exception) {
                    $message = $exception->getMessage();
                }
            }
        }

        return $message . '
        <h2>Publier un touite</h2>
        <form method="post" action="?action=publication">
            <label for="contenu">Votre touite :</label><br>
            <textarea id="contenu" name="contenu" maxlength="235" required></textarea><br>
            <button type="submit">Publier</button>
        </form>
    ';
    }
}