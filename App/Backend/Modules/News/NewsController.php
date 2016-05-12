<?php
namespace App\Backend\Modules\News;

use Entity\Tag;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentUserFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
use \OCFram\Session;
use OCFram\TextValidator;

class NewsController extends BackController
{
    public function executeDelete(HTTPRequest $Request)
    {
        $newsId = $Request->getData('id');

        $this->Managers->getManagerOf('News')->deleteNewsUsingId($newsId);
        $this->Managers->getManagerOf('Comments')->deleteCommentUsingNewsId($newsId);

        $this->App()->Session()->setFlash('La news a bien été supprimée !');

        $this->App()->HttpResponse()->redirect('/admin/');
    }

    public function executeDeleteComment(HTTPRequest $Request)
    {
        $this->Managers->getManagerOf('Comments')->deleteCommentUsingId($Request->getData('id'));

        $this->App()->Session()->setFlash('Le commentaire a bien été supprimé !');

        $this->App()->HttpResponse()->redirect('/');
    }

    public function executeIndex(HTTPRequest $Request)
    {
        $this->Page->addVar('title', 'Gestion des news');

        $Manager = $this->Managers->getManagerOf('News');

        if (Session::isAdmin()) {
            $this->Page->addVar('listeNews_a', $Manager->getNews_a());
            $this->Page->addVar('nombreNews', $Manager->count());
        } else {

            $listeNews_a = $Manager->getNewsUsingUserId($this->App()->Session()->getAttribute("authId"));
            $this->Page->addVar('listeNews_a', $listeNews_a);
            $this->Page->addVar('nombreNews', sizeof($listeNews_a));
        }
    }

    public function executeInsert(HTTPRequest $Request)
    {
        $this->processForm($Request);
        $this->Page->addVar('title', 'Ajout d\'une news');
    }

    public function executeUpdate(HTTPRequest $Request)
    {
        $this->processForm($Request);
        $this->Page->addVar('title', 'Modification d\'une news');
    }

    public function executeUpdateComment(HTTPRequest $Request)
    {
        $this->Page->addVar('title', 'Modification d\'un commentaire');

        if ($Request->method() == 'POST') {

            $Comment = new Comment([

                'id' => $Request->getData('id'),
                'news' => $Request->getData('news'),
                'auteur' => Session::isAuthenticated() ? $this->App()->Session()->getAttribute("authName") : $Request->postData('auteur'),
                'auteurId' => Session::isAuthenticated() ? $this->App()->Session()->getAttribute("authId") : Comment::AUTEUR_INCONNU,
                'contenu' => $Request->postData('contenu')

            ]);
        } else {

            $Comment = $this->Managers->getManagerOf('Comments')->getCommentUsingId($Request->getData('id'));

            if ($Comment == null)
                $this->App()->HttpResponse()->redirect404();

            if ($Comment->auteurId() != $this->App()->Session()->getAttribute("authId") && !$this->App()->Session()->isAdmin())
                $this->App()->HttpResponse()->redirect404();
        }

        $formBuilder = new CommentUserFormBuilder($Comment);
        $formBuilder->build();

        $Form = $formBuilder->form();

        $FormHandler = new FormHandler($Form, $this->Managers->getManagerOf('Comments'), $Request);

        if ($FormHandler->process()) {

            $this->App()->Session()->setFlash('Le commentaire a bien été modifié');
            $this->App()->HttpResponse()->redirect('/admin/');
        }

        $this->Page->addVar('Form', $Form->createView());
    }

    public function processForm(HTTPRequest $Request)
    {

        if ($Request->method() == 'POST') {

            $tags_a = array();
            foreach (explode(",", $Request->postData('tagString')) as $tag) {

                $Tag = new Tag([

                    'name' => strtolower(trim($tag)),

                ]);

                // si le tag n'existe pas on l'ajoute à la base
                if (!$this->Managers->getManagerOf('Tags')->existsTagUsingName($Tag->name())) {

                    if ((new TextValidator("error", ',\s'))->isValid($Tag->name()))
                        $Tag = $this->Managers->getManagerOf('Tags')->insertTag($Tag);

                } else {

                    $Tag = $this->Managers->getManagerOf('Tags')->getTagUsingName($Tag->name());
                }


                $tags_a[] = $Tag;
            }

            $News = new News([
                'auteur' => $this->App()->Session()->getAttribute("authId"),
                'titre' => $Request->postData('titre'),
                'contenu' => $Request->postData('contenu'),
                'tagString' => $Request->postData('tagString'),
                'tag' => $tags_a,
            ]);


            if ($Request->getExists('id')) {
                $News->setId($Request->getData('id'));

            }


        } else {

            // L'identifiant de la news est transmis si on veut la modifier
            if ($Request->getExists('id')) {

                $News = $this->Managers->getManagerOf('News')->getNewsUsingId($Request->getData('id'));

                if ($News == null)
                    $this->App()->HttpResponse()->redirect404();

                if ($News->auteur() != $this->App()->Session()->getAttribute("authId") && !$this->App()->Session()->isAdmin())
                    $this->App()->HttpResponse()->redirect404();

            } else {

                $News = new News();
            }

        }

        $FormBuilder = new NewsFormBuilder($News);
        $FormBuilder->build();
        $Form = $FormBuilder->form();

        $FormHandler = new FormHandler($Form, $this->Managers->getManagerOf('News'), $Request);

        if ($FormHandler->process()) {

            $this->App()->Session()->setFlash($News->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');
            $this->App()->HttpResponse()->redirect('/admin/');
        }

        $this->Page->addVar('Form', $Form->createView());

    }
}