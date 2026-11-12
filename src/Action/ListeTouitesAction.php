<?php

namespace Zayd54500\TouiteurSae\Action;

use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class ListeTouitesAction
{
    public function execute(): string
    {
        $utilisateurId = (int) ($_SESSION["utilisateur_id"] ?? 0);
        $repository = new TouiteRepository();
        $action = $_GET["action"] ?? "liste";

        if ($action === "mur") {
            if ($utilisateurId <= 0) {
                return "Selectionne un utilisateur pour afficher son mur";
            }

            $wall = $repository->getMurUtilisateur($utilisateurId);
        } else {
            $wall = $repository->getAll();
        }

        return $this->afficherTouite($wall);
    }
    public function afficherTouite($listeTouites)
    {
        usort($listeTouites, function ($a, $b) {
            return strcmp(
                $b["date_publication"],
                $a["date_publication"]
            );
        });

        $action = $_GET["action"] ?? "liste";
        $titre = ($action === "mur") ? "Mon mur" : "Fil d'actualité";
        $html = "<h2>{$titre}</h2>";

        if ($action === "mur") {
            $csrfToken = htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                'UTF-8'
            );
            $html .= "
        <form method='POST' action='?action=abonner-tag' class='mb-4'>
            <input type='hidden' name='csrf_token' value='{$csrfToken}'>
            <label for='tag' class='form-label'>
                S'abonner à un hashtag
            </label>

            <div class='d-flex gap-2'>
                <input
                    type='text'
                    id='tag'
                    name='tag'
                    class='form-control'
                    placeholder='Exemple : Linux'
                    maxlength='100'
                    required
                >

                <button type='submit' class='btn btn-primary'>
                    S'abonner
                </button>
            </div>
        </form>
    ";

            if ($action === "mur" && empty($listeTouites)) {
                $html .= "<p class='text-secondary'>
                            Aucune publication pour le moment.
                            Abonne-toi à un utilisateur ou à un hashtag.
                        </p>";
            }
        }
        foreach ($listeTouites as $touite) {
            $id = (int) $touite['id'];
            $pseudo = htmlspecialchars(
                $touite["pseudo"],
                ENT_QUOTES,
                "UTF-8"
            );
            $contenu = htmlspecialchars(
                $touite["contenu"],
                ENT_QUOTES,
                "UTF-8"
            );

            $contenu = preg_replace_callback(
                '/(?<!&)#[a-zA-Z0-9_]+/',
                function ($matches) {
                    $hashtag = $matches[0];
                    $tag = substr($hashtag, 1);

                    return '<a class="touite-hashtag" href="?action=tag&amp;tag='
                        . urlencode($tag)
                        . '">'
                        . $hashtag
                        . '</a>';
                },
                $contenu
            );

            $date = date(
                'd/m/Y H:i',
                strtotime($touite["date_publication"])
            );

            $initiale = htmlspecialchars(
                mb_strtoupper(mb_substr($touite["pseudo"], 0, 1)),
                ENT_QUOTES,
                "UTF-8"
            );

            $html .= "
                <article class='touite-card'>
                    <div class='d-flex gap-3'>
                        <div class='touite-avatar'>{$initiale}</div>

                        <div class='flex-grow-1'>
                            <div class='mb-2'>
                                <a href='?action=auteur&pseudo={$pseudo}' class='fw-bold text-decoration-none text-white'>{$pseudo}</a>
                                <span class='text-secondary ms-2'>{$date}</span>
                            </div>

                            <div class='touite-content'>{$contenu}</div>

                            <div class='touite-actions mt-3'>
                                <a href='?action=detail&amp;id={$id}'
                                class='touite-action'
                                aria-label='Voir les détails du touite'>
                                    <i class='bi bi-chat'></i>
                                    <span>Détails</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            ";
        }
        return $html;
    }
}
