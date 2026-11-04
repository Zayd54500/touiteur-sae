<?php

namespace Zayd54500\TouiteurSae\Classes;

class Hashtag {
    private $nom;
    private $nbSuivi;
    public function __construct($n){
        $this->nom = n;
        $this->nbSuivi = 0;
    }
    public function etreSuivi(){
        $this->nbSuivi++;
    }
    public function __get($propriete) {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }
        return null;
    }
}