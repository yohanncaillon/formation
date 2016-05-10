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

            $User = $this->managers->getManagerOf('Users')->authenticate($login, $password);

            if ($User != null) {

                if (password_verify($password, $User->password())) {

                    $this->App->session()->setAuthenticated(true, $User);
                    $this->App->httpResponse()->redirect('/');

                } else {

                    $this->App->session()->setFlash('Le mot de passe est incorrect.');
                }


            } else {

                $this->App->session()->setFlash('Le pseudo est incorrect.');
            }

        }
    }

    public function executeLogout(HTTPRequest $request)
    {

        session_unset();
        session_destroy();
        $this->App->session()->setAuthenticated(false, null);
        $this->App->httpResponse()->redirect('/');

    }
}