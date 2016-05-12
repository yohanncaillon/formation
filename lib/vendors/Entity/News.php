<?php
namespace Entity;

use \OCFram\Entity;
use OCFram\Router;

class News extends Entity
{
    const ADDED = "added";
    const MODIFIED = "modified";
    protected $auteur,
        $auteurName,
        $titre,
        $contenu,
        $tag_a,
        $tagString,
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

    public function setTag($tags)
    {

        $this->tag_a = $tags;
        if ($this->tagString == null) {

            foreach ($this->tag_a as $Tag) {

                $this->tagString .= $Tag->name() . ", ";
            }
            $this->tagString = substr($this->tagString, 0, (strlen($this->tagString) - 2));
        }

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
        return $this->tag_a;
    }

    public function tagHtml()
    {
        $retour = "<p> TAGS : ";
        foreach ($this->tag_a as $Tag) {

            $retour .= $Tag->tagHtml() . " ";
        }
        return $retour . "</p>";
    }

    /**
     * @return mixed
     */
    public function tagString()
    {
        return $this->tagString;
    }

    /**
     * @param mixed $tagString
     */
    public function setTagString($tagString)
    {
        $this->tagString = $tagString;
    }

    /**
     * @return mixed
     */
    public function auteurName()
    {
        return $this->auteurName;
    }

    /**
     * @param mixed $auteurName
     */
    public function setAuteurName($auteurName)
    {
        $this->auteurName = $auteurName;
    }

}