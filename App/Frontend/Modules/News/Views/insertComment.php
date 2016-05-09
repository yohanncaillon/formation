<?php
if(isset($commentaires)) {

    foreach ($commentaires as $comment) {

        $sessionData = "";
        if ($session->isAuthenticated()) {

            $sessionData .= "<a href='" . \OCFram\Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $comment->id())) . "'>Modifier</a> |";
            $sessionData .= "<a href='" . \OCFram\Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $comment->id())) . "'>Supprimer</a>";
        }

        echo "<fieldset><legend>Posté par <strong>" . htmlentities($comment->auteur()) . "</strong> le " . $comment->date()->format('d/m/Y à H\hi') . " " . $sessionData . "</legend> <p class='break'>" . htmlentities($comment->contenu()) . "</p></fieldset>";
    }
}