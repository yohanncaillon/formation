<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\User;
use \FormBuilder\LoginFormBuilder;

class ConnexionController extends BackController
{

    public function executeIndex(HTTPRequest $request)
    {

        $this->page->addVar('title', 'Connexion');

        if ($request->postExists('login')) {

            $login = $request->postData('login');
            $password = $request->postData('password');

            $user = $this->managers->getManagerOf('Users')->authenticate($login, $password);

            if ($user != null) {

                if (password_verify($password, $user->getPassword())) {

                    $this->app->session()->setAuthenticated(true, $user);
                    $this->app->httpResponse()->redirect('/');

                } else {

                    $this->app->session()->setFlash('Le mot de passe est incorrect.');
                }


            } else {

                $this->app->session()->setFlash('Le pseudo est incorrect.');
            }

        }
    }

    public function executeRegister(HTTPRequest $request)
    {

        $this->page->addVar('title', 'Ajout');
        if ($request->postExists('login')) {

            $login = $request->postData('login');
            $password = $request->postData('password');

            $result = $this->managers->getManagerOf('Users')->add($login, $password);

            if ($result == false) {

                $this->app->session()->setFlash('Une erreur est survenue.');

            } else {

                $this->app->session()->setFlash("L'utilisateur a été ajouté");
            }

        }

    }

    public function executeLogout(HTTPRequest $request)
    {

        session_unset();
        session_destroy();
        $this->app->session()->setAuthenticated(false, null);
        $this->app->httpResponse()->redirect('/');

    }
}