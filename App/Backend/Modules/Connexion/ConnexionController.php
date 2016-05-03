<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\User;

class ConnexionController extends BackController
{

    public function executeIndex(HTTPRequest $request) {

        $this->page->addVar('title', 'Connexion');
        if ($request->postExists('login')) {

            $login = $request->postData('login');
            $password = $request->postData('password');

            $user =  $this->managers->getManagerOf('Users')->authenticate($login, $password);

            if($user != null) {

                if(password_verify($password, $user->getPassword())) {

                    $this->app->session()->setAuthenticated(true);
                    $this->app->httpResponse()->redirect('/');

                } else {

                    $this->app->session()->setFlash('Le mot de passe est incorrect.');
                }

                
            } else {

                $this->app->session()->setFlash('Le pseudo est incorrect.');
            }

        }
    }

    public function executeLogout(HTTPRequest $request) {

        session_unset();
        session_destroy();
        $this->app->httpResponse()->redirect('/');

    }
}