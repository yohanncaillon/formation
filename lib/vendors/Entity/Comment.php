<?php
namespace Entity;

use \OCFram\Entity;
use OCFram\Router;

class Comment extends Entity
{
    protected $news,
        $auteur,
        $contenu,
        $date;

    const AUTEUR_INVALIDE = 1;
    const CONTENU_INVALIDE = 2;

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->contenu));
    }

    public function setNews($news)
    {
        $this->news = (int)$news;
    }

    public function setAuteur($auteur)
    {
        if (!is_string($auteur) || empty($auteur)) {
            $this->erreurs[] = self::AUTEUR_INVALIDE;
        }

        $this->auteur = $auteur;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreurs[] = self::CONTENU_INVALIDE;
        }

        $this->contenu = $contenu;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function news()
    {
        return $this->news;
    }

    public function auteur()
    {
        return $this->auteur;
    }

    public function contenu()
    {
        return $this->contenu;
    }

    public function date()
    {
        return $this->date;
    }

    public function toHtml($session)
    {

        $sessionData = "";
        if ($session->isAuthenticated()) {

            $sessionData .= "- <a href='" . Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $this->id())) . "'>Modifier</a> |";
            $sessionData .= "<a href='" . Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $this->id())) . "'> Supprimer</a>";
        }

        return "<fieldset><legend>Posté par <strong>" . htmlentities($this->auteur) . "</strong> le " . $this->date->format('d/m/Y à H\hi') . " " . $sessionData . "</legend> <p class=\"break\">" . htmlentities($this->contenu()) . "</p></fieldset>";
    }
}