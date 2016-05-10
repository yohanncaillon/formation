<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentUserFormBuilder;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\HTTPResponse;
use OCFram\Page;
use OCFram\Session;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $nombreNews = $this->App->config()->get('nombre_news');
        $nombreCaracteres = $this->App->config()->get('nombre_caracteres');

        $request->getData('page') != null ? $page = $request->getData('page') : $page = 0;

        // On ajoute une définition pour le titre.
        $this->page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

        // On récupère le manager des news.
        $Manager = $this->managers->getManagerOf('News');

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
        $this->page->addVar('listeNews_a', $listeNews_a);
        $this->page->addVar('pageNumber', $page);
        $this->page->addVar('next', $next);
    }

    public function executeShow(HTTPRequest $request)
    {
        $News = $this->managers->getManagerOf('News')->getNewsUsingId($request->getData('id'));
        $comment_a = $this->managers->getManagerOf('Comments')->getCommentUsingNewsId_a($News->id());
        $User = $this->managers->getManagerOf('Users')->getUserUsingId($News->auteur());

        if (empty($News)) {
            $this->App->httpResponse()->redirect404();
        }

        Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder(new Comment()) : $formBuilder = new CommentFormBuilder(New Comment());
        $formBuilder->build();
        $Form = $formBuilder->form();

        $this->page->addVar('Form', $Form->createView());
        $this->page->addVar('title', $News->titre());
        $this->page->addVar('News', $News);
        $this->page->addVar('comment_a', $comment_a);
        $this->page->addVar('User', $User);
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        $this->page->setType(Page::AJAX_PAGE);
        $error = false;
        try {

            if ($request->method() == 'POST') {

                if ($request->postData('auteur') != null && $this->managers->getManagerOf('Users')->existsMemberUsingName($request->postData('auteur')))
                    throw new \Exception("Le nom d'utilisateur existe déjà !");


                $Comment = new Comment([

                    'news' => $request->getData('news'),
                    'auteur' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authName") : $request->postData('auteur'),
                    'auteurId' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                    'contenu' => $request->postData('contenu')

                ]);

            } else {

                $Comment = new Comment;

            }
            Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder($Comment) : $formBuilder = new CommentFormBuilder($Comment);

            $formBuilder->build();
            $Form = $formBuilder->form();
            $formHandler = new FormHandler($Form, $this->managers->getManagerOf('Comments'), $request);

            $message = "Votre commentaire a bien été enregistré !";
            $error = !$formHandler->process();

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $this->page->addVar('erreur', $error);
        $this->page->addVar('message', $message);
        $this->page->addVar('comment_a', $this->managers->getManagerOf('Comments')->getCommentUsingNewsId($request->getData('news'), $request->postData('offsetId')));

    }
}