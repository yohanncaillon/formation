<?php
namespace Entity;

use \OCFram\Entity;
use OCFram\Router;
use OCFram\Session;

class Comment extends Entity implements \JsonSerializable
{
    protected $news,
        $auteur,
        $newsName,
        $auteurId,
        $contenu,
        $date;

    const AUTEUR_INVALIDE = 1;
    const CONTENU_INVALIDE = 2;
    const AUTEUR_INCONNU = 0;

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
            $this->erreur_a[] = self::AUTEUR_INVALIDE;
        }

        $this->auteur = $auteur;
    }

    public function setAuteurId($auteurId)
    {

        $this->auteurId = $auteurId;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreur_a[] = self::CONTENU_INVALIDE;
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

    public function auteurId()
    {
        return $this->auteurId;
    }

    /**
     * @return mixed
     */
    public function newsName()
    {
        return $this->newsName;
    }

    /**
     * @param mixed $newsName
     */
    public function setNewsName($newsName)
    {
        $this->newsName = $newsName;
    }

    public function jsonSerialize()
    {

        return [

            "auteur" => $this->auteur(),
            "contenu" => $this->contenu(),
            "html" => $this->toHtml()

        ];

    }

    public function toHtml()
    {
        $sessionData = "";
        $userData = "Posté par <strong>" . htmlentities($this->auteur) . "</strong>";

        if (Session::isAuthenticated()) {

            $sessionData .= "- <a href='" . Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $this->id())) . "'>Modifier</a> |";
            $sessionData .= "<a class='delete-comment' href='" . Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $this->id())) . "'> Supprimer</a>";
        }

        if ($this->auteurId != Comment::AUTEUR_INCONNU) {

            $userData = "Posté par <a href='" . Router::getInstance()->getRouteUrl("user", "Frontend", array("id" => $this->auteurId())) . "'><strong>" . htmlentities($this->auteur) . "</strong></a>";
        }

        return "<fieldset class='comment' data-id='" . $this->id() . "' ><legend>" . $userData . " le " . $this->date->format('d/m/Y à H\hi') . " " . $sessionData . "</legend> <p class=\"break\">" . htmlentities($this->contenu()) . "</p></fieldset>";
    }
}