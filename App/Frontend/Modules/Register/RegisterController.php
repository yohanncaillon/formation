<?php
/**
 * Created by PhpStorm.
 * User: ycaillon-morisseau
 * Date: 09/05/2016
 * Time: 16:42
 */
namespace App\Frontend\Modules\Register;

use FormBuilder\RegisterFormBuilder;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\User;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\HTTPResponse;
use OCFram\Page;

class RegisterController extends BackController
{

    public function executeRegister(HTTPRequest $Request)
    {

        $this->Page->addVar('title', 'Inscription');

        if ($Request->method() == 'POST') {

            $User = new User ([
                'name' => $Request->postData('name'),
                'password' => $Request->postData('password'),
                'status' => User::USER_WRITER,
                'email' => $Request->postData('email')
            ]);
            $password_confirm = $Request->postData('password_confirm');

        } else {

            $User = new User();
            $password_confirm = "";
        }

        $FormBuilder = new RegisterFormBuilder($User, $password_confirm);
        $FormBuilder->build();
        $Form = $FormBuilder->form();

        if ($Request->method() == 'POST' && $Form->isValid()) {

            try {
                $this->Managers->getManagerOf('Users')->insertUser($User);
                $this->App()->Session()->setAuthenticated(true, $User);
                $this->App()->HttpResponse()->redirect('/');

            } catch (\Exception $e) {

                switch ($e->getCode()) {

                    case 23000:
                        $message = "Le nom d'utilisateur existe déjà ! ";
                        break;

                    default:
                        $message = "Une erreur est survenue ! erreur " . $e->getMessage();

                }
            }
            $this->App()->Session()->setFlash($message);

        }
        $this->Page->addVar('Form', $Form->createView());

    }

}