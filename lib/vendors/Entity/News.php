<?php
namespace Entity;

use \OCFram\Entity;
use OCFram\Router;

class News extends Entity
{
    protected $auteur,
        $titre,
        $contenu,
        $tag,
        $dateAjout,
        $dateModif;

    const AUTEUR_INVALIDE = 1;
    const TITRE_INVALIDE = 2;
    const CONTENU_INVALIDE = 3;

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
    }

    // SETTERS //

    public function setTag($tags) {
        $this->tag = $tags;

    }

    public function setAuteur($auteur)
    {
        if (!is_string($auteur) || empty($auteur)) {
            $this->erreur_a[] = self::AUTEUR_INVALIDE;
        }

        $this->auteur = $auteur;
    }

    public function setTitre($titre)
    {
        if (!is_string($titre) || empty($titre)) {
            $this->erreur_a[] = self::TITRE_INVALIDE;
        }

        $this->titre = $titre;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreur_a[] = self::CONTENU_INVALIDE;
        }

        $this->contenu = $contenu;
    }

    public function setDateAjout(\DateTime $dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

    // GETTERS //

    public function auteur()
    {
        return $this->auteur;
    }

    public function titre()
    {
        return $this->titre;
    }

    public function contenu()
    {
        return $this->contenu;
    }

    public function dateAjout()
    {
        return $this->dateAjout;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }

    public function tag()
    {
        return $this->tag;
    }

    public function containsTag($tag) {

        $tag_a = explode(",", $this->tag);
        foreach ($tag_a as $t) {

            if (trim($t) == $tag)
                return true;
        }
        return false;
    }

    public function tagHtml()
    {
        $retour = "<p> TAGS : ";
        $tag_a = explode(",", $this->tag);
        foreach ($tag_a as $t) {

            $retour .= "<a href='". Router::getInstance()->getRouteUrl("tag", "Frontend", array("name" => trim($t))) . "'>". trim($t) ."</a> ";
        }
        return $retour . "</p>";
    }
}