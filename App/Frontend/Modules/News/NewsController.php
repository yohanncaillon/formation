<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\HTTPResponse;
use OCFram\Page;

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

        if (empty($news)) {
            $this->app->httpResponse()->redirect404();
        }

        $formBuilder = new CommentFormBuilder(new Comment());
        $formBuilder->build();
        $form = $formBuilder->form();

        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $comments);
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        $this->page->setType(Page::AJAX_PAGE);

        if ($request->method() == 'POST') {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu')
            ]);
        } else {
            $comment = new Comment;

        }
        $formBuilder = new CommentFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
        $message = "Votre commentaire a bien été enregistré !";

        try {

            $error = !$formHandler->process();

        }catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $this->page->addVar('erreur', $error);
        $this->page->addVar('message', $message);
        $this->page->addVar('comment', $this->managers->getManagerOf('Comments')->getListOf($request->getData('news'), $request->postData('offsetId')));

    }
}