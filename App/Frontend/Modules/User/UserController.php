<?php
namespace App\Frontend\Modules\User;

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

        $nombreNews = $this->App()->Config()->get('nombre_news');
        $nombreCaracteres = $this->App->Config()->get('nombre_caracteres');
        $User = $this->Managers->getManagerOf("Users")->getUserUsingId($Request->getData("id"));
        if ($User == null)
            $this->App->HttpResponse()->redirect404();

        $listeNews_a = $this->Managers->getManagerOf('News')->getNewsUsingUserId($User->id(), 0, $nombreNews);

        foreach ($listeNews_a as $News) {

            if (strlen($News->contenu()) > $nombreCaracteres) {
                $debut = substr($News->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $News->setContenu($debut);
            }

        }

        $this->Page->addVar('title', 'Liste des news de ' . $User->name());
        $this->Page->addVar('listeNews_a', $listeNews_a);
        $this->Page->addVar('User', $User);
        
    }


}
