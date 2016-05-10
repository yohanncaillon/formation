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

    public function executeIndex(HTTPRequest $request)
    {

        $nombreNews = $this->App->config()->get('nombre_news');
        $nombreCaracteres = $this->App->config()->get('nombre_caracteres');
        $User = $this->managers->getManagerOf("Users")->getUserUsingId($request->getData("id"));
        if ($User == null)
            $this->App->httpResponse()->redirect404();

        $listeNews_a = $this->managers->getManagerOf('News')->getNewsUsingUserId($User->id(), 0, $nombreNews);

        $this->page->addVar('title', 'Liste des news de ' . $User->name());
        $this->page->addVar('listeNews_a', $listeNews_a);
        $this->page->addVar('User', $User);
        
    }


}
