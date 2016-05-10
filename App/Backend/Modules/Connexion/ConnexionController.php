<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\User;
use \FormBuilder\LoginFormBuilder;

class ConnexionController extends BackController
{

    public function executeIndex(HTTPRequest $Request)
    {

        $this->Page->addVar('title', 'Connexion');

        if ($Request->postExists('login')) {

            $login = $Request->postData('login');
            $password = $Request->postData('password');

            $User = $this->Managers->getManagerOf('Users')->getUserUsingName($login);

            if ($User != null) {

                if (password_verify($password, $User->password())) {

                    $this->App()->Session()->setAuthenticated(true, $User);
                    $this->App()->httpResponse()->redirect('/');

                } else {

                    $this->App()->Session()->setFlash('Le mot de passe est incorrect.');
                }


            } else {

                $this->App()->Session()->setFlash('Le pseudo est incorrect.');
            }

        }
    }

    public function executeLogout(HTTPRequest $Request)
    {

        session_unset();
        session_destroy();
        $this->App()->Session()->setAuthenticated(false, null);
        $this->App()->httpResponse()->redirect('/');

    }
}