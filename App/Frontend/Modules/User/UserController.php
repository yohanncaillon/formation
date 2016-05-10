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

        $User = $this->Managers->getManagerOf("Users")->getUserUsingId($Request->getData("id"));
        if ($User == null)
            $this->App->httpResponse()->redirect404();

        $listeNews_a = $this->Managers->getManagerOf('News')->getNewsUsingUserId($User->id(), 0, $nombreNews);

        $this->Page->addVar('title', 'Liste des news de ' . $User->name());
        $this->Page->addVar('listeNews_a', $listeNews_a);
        $this->Page->addVar('User', $User);
        
    }


}
