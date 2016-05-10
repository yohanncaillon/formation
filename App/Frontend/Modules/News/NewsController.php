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
        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        $request->getData('page') != null ? $page = $request->getData('page') : $page = 0;

        // On ajoute une définition pour le titre.
        $this->page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

        // On récupère le manager des news.
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getList($page * $nombreNews, $nombreNews + 1);

        foreach ($listeNews as $news) {
            if (strlen($news->contenu()) > $nombreCaracteres) {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $news->setContenu($debut);
            }
        }
        $next = false;

        if (sizeof($listeNews) == $nombreNews + 1) {

            $next = true;
            array_pop($listeNews);
        }

        // On ajoute la variable $listeNews à la vue.
        $this->page->addVar('listeNews', $listeNews);
        $this->page->addVar('pageNumber', $page);
        $this->page->addVar('next', $next);
    }

    public function executeShow(HTTPRequest $request)
    {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
        $comments = $this->managers->getManagerOf('Comments')->getListOf($news->id());
        $user = $this->managers->getManagerOf('Users')->getUnique($news->auteur());

        if (empty($news)) {
            $this->app->httpResponse()->redirect404();
        }

        Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder(new Comment()) : $formBuilder = new CommentFormBuilder(New Comment());
        $formBuilder->build();
        $form = $formBuilder->form();

        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $comments);
        $this->page->addVar('user', $user);
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        $this->page->setType(Page::AJAX_PAGE);
        $error = false;
        try {

            if ($request->method() == 'POST') {

                if ($request->postData('auteur') != null && $this->managers->getManagerOf('Users')->existsMemberUsingName($request->postData('auteur')))
                    throw new \Exception("Le nom d'utilisateur existe déjà !");


                $comment = new Comment([

                    'news' => $request->getData('news'),
                    'auteur' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authName") : $request->postData('auteur'),
                    'auteurId' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                    'contenu' => $request->postData('contenu')

                ]);

            } else {

                $comment = new Comment;

            }
            Session::isAuthenticated() ? $formBuilder = new CommentUserFormBuilder($comment) : $formBuilder = new CommentFormBuilder($comment);

            $formBuilder->build();
            $form = $formBuilder->form();
            $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

            $message = "Votre commentaire a bien été enregistré !";


            $error = !$formHandler->process();

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $this->page->addVar('erreur', $error);
        $this->page->addVar('message', $message);
        $this->page->addVar('comment_a', $this->managers->getManagerOf('Comments')->getListOf($request->getData('news'), $request->postData('offsetId')));

    }
}