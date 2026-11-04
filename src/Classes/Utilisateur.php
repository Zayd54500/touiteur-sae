<?php
namespace Zayd54500\TouiteurSae\Classes;
class Utilisateur {
    private $pseudo;
    private $listeComptesAbonnes;
    private $nbAbos;
    private $listeCompteSuivis;
    private $nbSuivis;
    private $listeHashtagsSuivis;
    public function __construct($p){
        $this->pseudo = $p;
        $this->listeComptesAbonnee = [];
        $this->listeCompteSuivis = [];
        $this->listeHashtagsSuivis = [];
        $this->nbAbos=0;
        $this->nbSuivis=0;
    }
    public function __get($propriete)
    {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }
        return null;
    }
    public function suivreUtilisateur(Utilisateur $user){
        $this->listeComptesSuivis[$user->__get('pseudo')] = true;
        $this->nbSuivis++;
        $user->etreSuivi($this);
    }
    public function etreSuivi($user){
        $this->listeComptesAbonnes[$user->__get('pseudo')] = true;
        $this->nbAbos++;
    }
}