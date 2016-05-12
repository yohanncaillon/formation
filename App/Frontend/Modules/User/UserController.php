<?php
namespace App\Frontend\Modules\User;

use Entity\News;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\User;

use \OCFram\FormHandler;
use OCFram\HTTPResponse;
use OCFram\Page;
use OCFram\Session;

class UserController extends BackController
{

    public function executeIndex(HTTPRequest $Request)
    {

        $nombreCaracteres = $this->App->Config()->get('nombre_caracteres');
        $User = $this->Managers->getManagerOf("Users")->getUserUsingId($Request->getData("id"));
        if ($User == null)
            $this->App->HttpResponse()->redirect404();

        $listeNews_a = $this->Managers->getManagerOf('News')->getNewsUsingUserId($User->id());
        $listeComment_a = $this->Managers->getManagerOf('Comments')->getCommentUsingUserId_a($User->id());

        $tab_a = array();
        foreach ($listeNews_a as $News) {

            if (strlen($News->contenu()) > $nombreCaracteres) {
                $debut = substr($News->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $News->setContenu($debut);
            }


            if ($News->dateModif() == $News->dateAjout()) {

                $tab_a[] = [
                    "id" => $News->id(),
                    "date" => $News->dateAjout(),
                    "action" => News::ADDED,
                    "newsName" => $News->titre(),
                    "content" => $News->contenu(),
                ];
            } else {

                $tab_a[] = [
                    "id" => $News->id(),
                    "date" => $News->dateAjout(),
                    "action" => News::ADDED,
                    "newsName" => $News->titre(),
                    "content" => $News->contenu(),
                ];

                $tab_a[] = [
                    "id" => $News->id(),
                    "date" => $News->dateModif(),
                    "action" => News::MODIFIED,
                    "newsName" => $News->titre(),
                    "content" => $News->contenu(),
                ];
            }
        }

        foreach ($listeComment_a as $Comment) {

            if (strlen($Comment->contenu()) > $nombreCaracteres) {
                $debut = substr($Comment->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $Comment->setContenu($debut);
            }

            $tab_a[] = [
                "id" => $Comment->news(),
                "date" => $Comment->date(),
                "newsName" => $Comment->newsName(),
                "content" => $Comment->contenu(),
            ];
        }
        uasort($tab_a, function ($element1, $element2) {

            return $element1["date"] > $element2["date"] ? -1 : 1;

        });

        $this->Page->addVar('title', 'Liste des actions de ' . $User->name());
        $this->Page->addVar('listeAction_a', $tab_a);
        $this->Page->addVar('User', $User);

    }

}
