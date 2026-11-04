<?php

namespace Zayd54500\TouiteurSae\Classes;

use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class Database {
    private $listeUtilisateur;
    private $listeTouite;
    public function __construct(TouiteRepository  $repo){
        $this->listeTouite = $repo->getAll();
        for ($i =0; $i<count($this->listeTouite); $i++){
            $this->listeUtilisateur[$this->listeTouite[$i]] = new Utilisateur($this->listeTouite[$i]['pseudo']);
        }
    }
    public function __get($propriete) {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }
        return null;
    }
}