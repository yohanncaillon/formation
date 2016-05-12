<?php
namespace App\Frontend\Modules\Tag;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentUserFormBuilder;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\HTTPResponse;
use OCFram\Page;
use OCFram\Session;

class TagController extends BackController
{

    public function executeIndex(HTTPRequest $Request)
    {

        $nombreNews = $this->App()->Config()->get('nombre_news');
        $nombreCaracteres = $this->App->Config()->get('nombre_caracteres');
        $Tag = $this->Managers->getManagerOf('Tags')->getTagUsingName($Request->getData("name"));

        // On ajoute une définition pour le titre.
        $this->Page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

        // On récupère le manager des news.
        $Manager = $this->Managers->getManagerOf('News');

        $listeNews_a = $Manager->getNewsUsingTag_a($Tag, 0, $nombreNews);

        foreach ($listeNews_a as $News) {

            if (strlen($News->contenu()) > $nombreCaracteres) {
                $debut = substr($News->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $News->setContenu($debut);
            }

        }

        // On ajoute la variable $listeNews à la vue.
        $this->Page->addVar('listeNews_a', $listeNews_a);
        $this->Page->addVar('tag', $Tag);
    }


    public function executeSearch(HTTPRequest $Request)
    {
        $this->Page->setType(Page::AJAX_PAGE);
        $this->Page->addVar('erreur', false);
        $this->Page->addVar('message', "Tag search");
        $this->Page->addVar('data_a', $this->Managers->getManagerOf('Tags')->searchTagUsingName_a($Request->postData("name")));

    }
}