<?php
namespace App\Backend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentUserFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
use OCFram\Session;

class NewsController extends BackController
{
    public function executeDelete(HTTPRequest $request)
    {
        $newsId = $request->getData('id');

        $this->managers->getManagerOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);

        $this->app->session()->setFlash('La news a bien été supprimée !');

        $this->app->httpResponse()->redirect('/admin/');
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $this->managers->getManagerOf('Comments')->delete($request->getData('id'));

        $this->app->session()->setFlash('Le commentaire a bien été supprimé !');

        $this->app->httpResponse()->redirect('/');
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des news');

        $manager = $this->managers->getManagerOf('News');

        if (Session::isAdmin()) {
            $this->page->addVar('listeNews', $manager->getList());
            $this->page->addVar('nombreNews', $manager->count());
        } else {

            $listeNews = $manager->getNewsUsingUserId($this->app->session()->getAttribute("authId"));
            $this->page->addVar('listeNews', $listeNews);
            $this->page->addVar('nombreNews', sizeof($listeNews));
        }
    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'une news');
    }

    public function executeUpdateComment(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Modification d\'un commentaire');

        if ($request->method() == 'POST') {

            $comment = new Comment([

                'id' => $request->getData('id'),
                'news' => $request->getData('news'),
                'auteur' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authName") : $request->postData('auteur'),
                'auteurId' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                'contenu' => $request->postData('contenu')

            ]);
        } else {
            $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
        }

        $formBuilder = new CommentUserFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process()) {
            $this->app->session()->setFlash('Le commentaire a bien été modifié');

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {

            $news = new News([
                'auteur' => $this->app()->session()->getAttribute("authId"),
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);


            if ($request->getExists('id')) {
                $news->setId($request->getData('id'));
            }

            if ($this->managers->getManagerOf('News')->save($news)) {

                $this->app->session()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

                $this->app->httpResponse()->redirect('/admin/');
            }


        } else {

            // L'identifiant de la news est transmis si on veut la modifier
            if ($request->getExists('id')) {

                $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
            } else {

                $news = new News;
            }

        }

        $formBuilder = new NewsFormBuilder($news);
        $formBuilder->build();
        $form = $formBuilder->form();
        $this->page->addVar('form', $form->createView());

    }
}