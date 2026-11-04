<?php
namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class TouitesAuteurAction { 
    public function execute(): string { 
        $pseudo = $_GET["pseudo"] ?? "";

        if (!is_string($pseudo)) { 
            return "Pseudo invalide";
        } else { 
            $pseudo = trim($pseudo);
        }

        if ($pseudo === "") { 
            return "Pseudo invalide";
        }

        $repository = new TouiteRepository();
        $touites = $repository->getByAuteur($pseudo);
        if (empty($touites)) { 
            return "Aucun touite pour cet auteur";
        }

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

        $html = "<h2>" . "Touites de " . htmlspecialchars($pseudo, ENT_QUOTES, "UTF-8") . "</h2>";

        foreach ($touites as $touite) { 
            $html .= "<p>" . htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") . "</p>" .
            "<small>" . htmlspecialchars($touite["date_publication"]) . "</small>";
        }
        return $html;
    }
}
?>