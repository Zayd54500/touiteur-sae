<?php
namespace Zayd54500\TouiteurSae\Action;
use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class TouitesTagAction { 
    public function execute(): string { 
        $tag = $_GET["tag"] ?? "";

        if (is_string($tag) === false) { 
            return "tag invalide";
        }

        $tag = trim($tag);

        if ($tag === "") { 
            return "tag invalide";
        }

        $repository = new TouiteRepository();
        $touites = $repository->getByTag($tag);

        if (empty($touites)) { 
            return "Aucun touite trouvé pour ce tag";
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

        $html = "<h2>" . "Touites associés  à #" . htmlspecialchars($tag, ENT_QUOTES, "UTF-8") .
        "</h2>"; 

        foreach($touites as $touite) { 
            $html .= "<p>" . "<strong>" . htmlspecialchars($touite["pseudo"], ENT_QUOTES, "UTF-8") .
            "</strong>" . " : " .
            htmlspecialchars($touite["contenu"], ENT_QUOTES, "UTF-8") .
            "</p>" . 
            "<small>" . htmlspecialchars($touite["date_publication"], ENT_QUOTES, "UTF-8") .
            "</small>";  
        }

        return $html;
    }
}
?>