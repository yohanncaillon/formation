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
use \OCFram\Page;

class RegisterController extends BackController
{

    public function executeRegister(HTTPRequest $Request)
    {
        $this->Page->addVar('title', 'Inscription');
        $message ="";

        if ($Request->method() == 'POST') {


            $User = new User ([
                'name' => $Request->postData('name'),
                'password' => $Request->postData('password'),
                'status' => User::USER_WRITER,
                'email' => $Request->postData('email'),
                'password_confirm' => $Request->postData('password_confirm'),
            ]);

        } else {

            $User = new User();

        }

        $FormBuilder = new RegisterFormBuilder($User);
        $FormBuilder->build();
        $Form = $FormBuilder->form();

        if ($Request->method() == 'POST' && $Form->isValid()) {

            try {
                $this->Managers->getManagerOf('Users')->insertUser($User);
                $this->App()->Session()->setAuthenticated(true, $User);
                if (!$Request->isAjax())
                    $this->App()->HttpResponse()->redirect('/');

            } catch (\Exception $e) {

                switch ($e->getCode()) {

                    case 23000:
                        $message = "Le nom d'utilisateur existe déjà ! ";
                        break;

                    default:
                        $message = "Une erreur est survenue ! erreur " . $e->getMessage();

                }
                $this->App()->Session()->setFlash($message);
                $this->Page->addVar('erreur', true);
            }

            $this->Page->addVar('erreur', false);


        } else {

            $this->Page->addVar('erreur', true);
        }

        $this->Page->addVar('Form', $Form->createView());
        $this->Page->addVar('message', $message);

    }

    public function executeCheckName(HTTPRequest $Request) {

        $this->Page->setType(Page::AJAX_PAGE);
        $bool = $this->Managers->getManagerOf('Users')->existsMemberUsingName($Request->postData("name"));
        $this->Page->addVar('erreur', $bool);
        $this->Page->addVar('bool', "");

        $this->Page->addVar('message', $bool ? "Ce nom n'est pas disponible !" : "Ce nom est disponible");
    }

}