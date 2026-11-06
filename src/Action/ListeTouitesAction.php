<?php
namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Classes\Database;
use Zayd54500\TouiteurSae\Classes\Utilisateur;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class ListeTouitesAction {
    private Database $baseDeDonne;
    private Utilisateur $utilisateur;
    public function __construct($db, $u){
        $this->baseDeDonne = $db;
        $this->utilisateur = $u;
    }

    public function execute(): string
    {
        $tab = $this->baseDeDonne->__get("listeTouite");
        $wall = [];
        $compteur = 0;
        for ($i = 0; $i < min(20, count($tab)); $i++) {
            $elem = $tab[$i];
            if (isset($this->utilisateur->__get("listeComptesAbonnes")[$elem["pseudo"]])) {
                $wall[] = $elem;
                $compteur++;
            } else {
                preg_match_all('/#[a-zA-Z0-9_]+/', $elem["contenu"], $matches);
                foreach ($matches[0] as $tag) {
                    if (isset($this->utilisateur->__get('listeHashtagsSuivis')[$tag])) {
                        $wall[] = $elem;
                        $compteur++;
                        break;
                    }
                }
            }
        }
        foreach ($tab as $touite) {
            if (count($wall) >= 20) {
                break;
            }

            if (!in_array($touite, $wall, true)) {
                    $wall[] = $touite;
            }
        }
        return $this->afficherTouite($wall);
    }
    public function afficherTouite($listeTouites){
        usort($listeTouites, function ($a, $b) {
            return strcmp(
                $b["date_publication"],
                $a["date_publication"]
            );
        });

        $html = "<h2>Fil d'actualité</h2>";
        foreach($listeTouites as $touite) {
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
                '/#[a-zA-Z0-9_]+/',
                function ($matches) {
                    $hashtag = $matches[0];
                    $tag = substr($hashtag,1);

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
                                <span class='fw-bold'>{$pseudo}</span>
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

?>