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

        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');
        $user = $this->managers->getManagerOf("Users")->getUnique($request->getData("id"));
        if ($user == null)
            $this->app->httpResponse()->redirect404();

        $listeNews = $this->managers->getManagerOf('News')->getNewsUsingUserId($user->id(), 0, $nombreNews);

        $this->page->addVar('title', 'Liste des news de ' . $user->name());
        $this->page->addVar('listeNews', $listeNews);
        $this->page->addVar('user', $user);
    }


}
