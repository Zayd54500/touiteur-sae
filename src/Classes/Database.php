<?php

namespace Zayd54500\TouiteurSae\Classes;

use Zayd54500\TouiteurSae\Repository\TouiteRepository;

class Database {
    private $listeUtilisateur;
    private $listeTouite;
    public function __construct(TouiteRepository  $repo){
        $this->listeTouite = $repo->getAll();
        $this->listeUtilisateur = [];
        foreach ($this->listeTouite as $touite) {
            $pseudo = $touite["pseudo"];
            if(!isset($this->listeUtilisateur[$pseudo])) {
                $this->listeUtilisateur[$pseudo] = new Utilisateur($pseudo);
            }
        }
    }
    public function __get($propriete) {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }
        return null;
    }
}