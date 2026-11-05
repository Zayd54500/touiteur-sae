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
        for ($i = 0; $i < 20; $i++) {
            $elem = $tab[$i];
            if ($this->utilisateur->__get("listeComptesAbonnes")[$elem["pseudo"]]) {
                $wall[] = $elem;
                $compteur++;
            } else {
                $tabHash = $elem["hashtag"];
                foreach ($elem as $tag) {
                    if ($this->utilisateur->__get('listeHashtagsSuivis')[$tag]) {
                        $wall[] = $elem;
                        $compteur++;
                        break;
                    }
                }
            }
        }
        for ($i = $compteur; $i < 20; $i++) {
            $wall[] = $tab[$i];
        }
        return $this->afficherTouite($wall);
    }
    public function afficherTouite($listeTouites){
        usort($listeTouites, function ($a, $b) {
            $dateA = \DateTime::createFromFormat("d/m/Y", $a["date_publication"]);
            $dateB = \DateTime::createFromFormat("d/m/Y", $b["date_publication"]);

            if ($dateA > $dateB) { 
                return -1; 
            } else if ($dateA < $dateB) { 
                return 1;
            } else { 
                return 0;
            }
        });
        
        $html = "<h2>Fil d'actualité</h2>";
        foreach ($listeTouites as $touite) {
            preg_match_all('/#[a-zA-Z0-9_]+/', $touite["contenu"], $matches);
            $html .= "<p>" . htmlspecialchars($touite["pseudo"], ENT_QUOTES, "UTF-8")  . 
            " : " . "<a href='?action=detail&id=". $touite["id"] ."'>" .  htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") . "</a><br>" .
            "publié le : " . htmlspecialchars($touite["date_publication"], ENT_QUOTES, "UTF-8") . "</p>";
            foreach($matches[0] as $hashtag) { 
                $tag = substr($hashtag, 1);
                $html .=  '<a href="?action=tag&amp;tag=' . urlencode($tag) . '">' . 
                htmlspecialchars($hashtag, ENT_QUOTES, "UTF-8") . '</a>';
            }
        }
        
        return $html;
    }
}
?>