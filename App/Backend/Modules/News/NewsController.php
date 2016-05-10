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

        $this->managers->getManagerOf('News')->deleteNewsUsingId($newsId);
        $this->managers->getManagerOf('Comments')->deleteCommentUsingNewsId($newsId);

        $this->App->session()->setFlash('La news a bien été supprimée !');

        $this->App->httpResponse()->redirect('/admin/');
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $this->managers->getManagerOf('Comments')->deleteCommentUsingId($request->getData('id'));

        $this->App->session()->setFlash('Le commentaire a bien été supprimé !');

        $this->App->httpResponse()->redirect('/');
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des news');

        $Manager = $this->managers->getManagerOf('News');

        if (Session::isAdmin()) {
            $this->page->addVar('listeNews_a', $Manager->getNews_a());
            $this->page->addVar('nombreNews', $Manager->count());
        } else {

            $listeNews_a = $Manager->getNewsUsingUserId($this->App->session()->getAttribute("authId"));
            $this->page->addVar('listeNews_a', $listeNews_a);
            $this->page->addVar('nombreNews', sizeof($listeNews_a));
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

            $Comment = new Comment([

                'id' => $request->getData('id'),
                'news' => $request->getData('news'),
                'auteur' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authName") : $request->postData('auteur'),
                'auteurId' => Session::isAuthenticated() ? $this->app()->session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                'contenu' => $request->postData('contenu')

            ]);
        } else {
            $Comment = $this->managers->getManagerOf('Comments')->getCommentUsingId($request->getData('id'));
        }

        $formBuilder = new CommentUserFormBuilder($Comment);
        $formBuilder->build();

        $Form = $formBuilder->form();

        $FormHandler = new FormHandler($Form, $this->managers->getManagerOf('Comments'), $request);

        if ($FormHandler->process()) {

            $this->App->session()->setFlash('Le commentaire a bien été modifié');
            $this->App->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('Form', $Form->createView());
    }

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {

            $News = new News([
                'auteur' => $this->app()->session()->getAttribute("authId"),
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);


            if ($request->getExists('id')) {
                $News->setId($request->getData('id'));
            }

            if ($this->managers->getManagerOf('News')->save($News)) {

                $this->App->session()->setFlash($News->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');
                $this->App->httpResponse()->redirect('/admin/');
            }


        } else {

            // L'identifiant de la news est transmis si on veut la modifier
            if ($request->getExists('id')) {

                $News = $this->managers->getManagerOf('News')->getNewsUsingId($request->getData('id'));
            } else {

                $News = new News();
            }

        }

        $FormBuilder = new NewsFormBuilder($News);
        $FormBuilder->build();
        $Form = $FormBuilder->form();
        $this->page->addVar('Form', $Form->createView());

    }
}