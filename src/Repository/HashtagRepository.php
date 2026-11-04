<?php

namespace Zayd54500\TouiteurSae\Repository;

use InvalidArgumentException;

class HashtagRepository
{
    public function __construct()
    {
        if (!isset($_SESSION['hashtags'])) {
            $_SESSION['hashtags'] = [];
        }

        if (!isset($_SESSION['touite_hashtags'])) {
            $_SESSION['touite_hashtags'] = [];
        }
    }

    public function enregistrer(string $nom): int
    {
        $nom = trim($nom);

        if ($nom === '') {
            throw new InvalidArgumentException(
                'Le nom du hashtag ne peut pas être vide.'
            );
        }

        foreach ($_SESSION['hashtags'] as $hashtag) {
            if (strcasecmp($hashtag['nom'], $nom) === 0) {
                return $hashtag['id'];
            }
        }

        $nouvelId = count($_SESSION['hashtags']) + 1;

        $_SESSION['hashtags'][] = [
            'id' => $nouvelId,
            'nom' => $nom
        ];

        return $nouvelId;
    }

    public function associer(int $touiteId, int $hashtagId): void
    {
        foreach ($_SESSION['touite_hashtags'] as $association) {
            if (
                $association['touite_id'] === $touiteId
                && $association['hashtag_id'] === $hashtagId
            ) {
                return;
            }
        }

        $_SESSION['touite_hashtags'][] = [
            'touite_id' => $touiteId,
            'hashtag_id' => $hashtagId
        ];
    }
}