<?php

namespace Zayd54500\TouiteurSae\Action;

use InvalidArgumentException;
use Zayd54500\TouiteurSae\Repository\HashtagRepository;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;
use Zayd54500\TouiteurSae\Service\HashtagExtractor;

class PublicationTouiteAction
{
    private TouiteRepository $touiteRepository;
    private HashtagExtractor $hashtagExtractor;
    private HashtagRepository $hashtagRepository;

    public function __construct(
        TouiteRepository $touiteRepository,
        HashtagExtractor $hashtagExtractor,
        HashtagRepository $hashtagRepository
    ) {
        $this->touiteRepository = $touiteRepository;
        $this->hashtagExtractor = $hashtagExtractor;
        $this->hashtagRepository = $hashtagRepository;
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

        $touiteId = $this->touiteRepository->enregistrer(
            $contenu,
            $utilisateurId
        );

        $hashtags = $this->hashtagExtractor->extraire($contenu);

        foreach ($hashtags as $hashtag) {
            $hashtagId = $this->hashtagRepository->enregistrer($hashtag);
            $this->hashtagRepository->associer($touiteId, $hashtagId);
        }

        return $touiteId;
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
                    $utilisateurId = filter_var(
                        $_SESSION["utilisateur_id"] ?? null,
                        FILTER_VALIDATE_INT
                    );

                    if ($utilisateurId === false || $utilisateurId === null || $utilisateurId <= 0) {
                        throw new InvalidArgumentException("Selectionne un utilisateur avant de publier");
                    }

                    $id = $this->publier($contenu, $utilisateurId);
                    $message = 'Touite publié avec succès. Identifiant : ' . $id;
                } catch (InvalidArgumentException $exception) {
                    $message = $exception->getMessage();
                }
            }
        }
        $csrfToken = htmlspecialchars(
            $_SESSION["csrf_token"],
            ENT_QUOTES,
            'UTF-8'
        );
        return $message . '
        <div class="publication-container">
            <h2 class="mb-4">Publier un touite</h2>

            <form method="post" action="?action=publication" class="publication-form">
                <input type="hidden" name="csrf_token" value="' . $csrfToken . '">
                <div class="d-flex gap-3">
                    <div class="touite-avatar">Z</div>

                    <div class="flex-grow-1">
                        <label for="contenu" class="visually-hidden">Votre touite</label>

                        <textarea
                            id="contenu"
                            name="contenu"
                            class="publication-textarea"
                            placeholder="Quoi de neuf ?"
                            maxlength="235"
                            rows="4"
                            required
                        ></textarea>

                        <div class="publication-footer">
                            <span id="compteur-caracteres" class="text-secondary">
                            0 / 235
                            </span>
                            <button type="submit" class="btn-publier border-0">
                                Publier
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        ';
    }
}
