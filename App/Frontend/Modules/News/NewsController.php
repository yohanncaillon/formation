<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentUserFormBuilder;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\Page;
use OCFram\Session;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $Request)
    {
        $nombreNews = $this->App()->Config()->get('nombre_news');
        $nombreCaracteres = $this->App()->Config()->get('nombre_caracteres');

        $Request->getData('page') != null ? $page = $Request->getData('page') : $page = 0;

        // On ajoute une définition pour le titre.
        $this->Page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

        // On récupère le manager des news.
        $Manager = $this->Managers->getManagerOf('News');

        $listeNews_a = $Manager->getNews_a($page * $nombreNews, $nombreNews + 1);

        foreach ($listeNews_a as $News) {

            if (strlen($News->contenu()) > $nombreCaracteres) {
                $debut = substr($News->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $News->setContenu($debut);
            }

        }
        $next = false;

        if (sizeof($listeNews_a) == $nombreNews + 1) {

            $next = true;
            array_pop($listeNews_a);
        }

        // On ajoute la variable $listeNews à la vue.
        $this->Page->addVar('listeNews_a', $listeNews_a);
        $this->Page->addVar('pageNumber', $page);
        $this->Page->addVar('next', $next);
    }

    public function executeShow(HTTPRequest $Request)
    {
        $News = $this->Managers->getManagerOf('News')->getNewsUsingId($Request->getData('id'));
        $comment_a = $this->Managers->getManagerOf('Comments')->getCommentUsingNewsId_a($News->id());
        $User = $this->Managers->getManagerOf('Users')->getUserUsingId($News->auteur());

        if (empty($News)) {
            $this->App()->HttpResponse()->redirect404();
        }

        Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder(new Comment()) : $formBuilder = new CommentFormBuilder(New Comment());
        $formBuilder->build();
        $Form = $formBuilder->form();

        $authCheck = $News->auteur() == $this->App()->Session()->getAttribute("authId") || $this->App()->Session()->isAdmin();

        $this->Page->addVar('authCheck', $authCheck);
        $this->Page->addVar('Form', $Form->createView());
        $this->Page->addVar('title', $News->titre());
        $this->Page->addVar('News', $News);
        $this->Page->addVar('comment_a', $comment_a);
        $this->Page->addVar('User', $User);
    }

    public function executeInsertComment(HTTPRequest $Request)
    {
        $this->Page->setType(Page::AJAX_PAGE);
        $error = false;
        try {

            if ($Request->method() == 'POST') {

                if ($Request->postData('auteur') != null && $this->Managers->getManagerOf('Users')->existsMemberUsingName($Request->postData('auteur')))
                    throw new \Exception("Le nom d'utilisateur existe déjà !");


                $Comment = new Comment([

                    'news' => $Request->getData('news'),
                    'auteur' => Session::isAuthenticated() ? $this->App()->Session()->getAttribute("authName") : $Request->postData('auteur'),
                    'auteurId' => Session::isAuthenticated() ? $this->App()->Session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                    'contenu' => $Request->postData('contenu')

                ]);

            } else {

                $Comment = new Comment;

            }
            Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder($Comment) : $formBuilder = new CommentFormBuilder($Comment);

            $formBuilder->build();
            $Form = $formBuilder->form();
            $formHandler = new FormHandler($Form, $this->Managers->getManagerOf('Comments'), $Request);

            $message = "Votre commentaire a bien été enregistré !";
            $error = !$formHandler->process();

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $this->Page->addVar('erreur', $error);
        $this->Page->addVar('message', $message);
        $this->Page->addVar('comment_a', $this->Managers->getManagerOf('Comments')->getCommentUsingNewsId_a($Request->getData('news'), $Request->postData('offsetId')));

    }
}