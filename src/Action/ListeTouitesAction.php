<?php
namespace Zayd54500\TouiteurSae\Action;

class ListeTouitesAction { 
    public function execute(): string { 
        $touites = [ 
            [
                "id" => 3,
                "pseudo" => "Zayd",
                "contenu" => "Je suis le meilleur touitos de ce réseau", 
                "date_publication" => "01/11/2026"
            ],
            [
                "id" => 1,
                "pseudo" => "Vincent",
                "contenu" => "On test ce réseau de merde pour voir ^^",
                "date_publication" => "03/11/2026"
            ], 
            [
                "id" => 2,
                "pseudo" => "Aminou",
                "contenu" => "Je pense que vincent est une merde",
                "date_publication" => "02/11/2026"
            ]
        ];

        usort($touites, function ($a, $b) {
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
        foreach ($touites as $touite) { 
            $html .= "<p>" . htmlspecialchars($touite["pseudo"], ENT_QUOTES, "UTF-8")  . 
            " : " . "<a href='?action=detail&id=". $touite["id"] ."'>" .  htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") . "</a><br>" .
            "publié le : " . htmlspecialchars($touite["date_publication"], ENT_QUOTES, "UTF-8") . "</p>";
        }
        return $html;
    }
}
?>