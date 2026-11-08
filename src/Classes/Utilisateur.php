<?php

namespace Zayd54500\TouiteurSae\Classes;

class Utilisateur
{
    private string $pseudo;
    private array $listeComptesAbonnes;
    private int $nbAbos;
    private array $listeComptesSuivis;
    private int $nbSuivis;
    private array $listeHashtagsSuivis;

    public function __construct(string $pseudo)
    {
        $this->pseudo = $pseudo;
        $this->listeComptesAbonnes = [];
        $this->listeComptesSuivis = [];
        $this->listeHashtagsSuivis = [];
        $this->nbAbos = 0;
        $this->nbSuivis = 0;
    }

    public function __get(string $propriete)
    {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }

        return null;
    }

    public function suivreUtilisateur(Utilisateur $utilisateur): void
    {
        $pseudo = $utilisateur->__get('pseudo');

        if (isset($this->listeComptesSuivis[$pseudo])) {
            return;
        }

        $this->listeComptesSuivis[$pseudo] = true;
        $this->nbSuivis++;

        $utilisateur->etreSuivi($this);
    }

    public function etreSuivi(Utilisateur $utilisateur): void
    {
        $pseudo = $utilisateur->__get('pseudo');

        if (isset($this->listeComptesAbonnes[$pseudo])) {
            return;
        }

        $this->listeComptesAbonnes[$pseudo] = true;
        $this->nbAbos++;
    }

    public function suivreTag(Hashtag $tag): void
    {
        $nom = $tag->__get('nom');

        if (isset($this->listeHashtagsSuivis[$nom])) {
            return;
        }

        $this->listeHashtagsSuivis[$nom] = true;
        $tag->etreSuivi();
    }
}