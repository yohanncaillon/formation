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


    public function executeRegister(HTTPRequest $request)
    {

        $this->page->addVar('title', 'Inscription');

        if ($request->method() == 'POST') {

            $User = new User ([
                'name' => $request->postData('name'),
                'password' => $request->postData('password'),
                'status' => 2,
                'email' => $request->postData('email')
            ]);
            $password_confirm = $request->postData('password_confirm');

        } else {

            $User = new User();
            $password_confirm = "";
        }

        $FormBuilder = new RegisterFormBuilder($User, $password_confirm);
        $FormBuilder->build();
        $Form = $FormBuilder->form();

        if ($request->method() == 'POST' && $Form->isValid()) {

            try {
                $this->managers->getManagerOf('Users')->insertUser($User);
                $this->app()->session()->setAuthenticated(true, $User);
                $this->App->httpResponse()->redirect('/');

            } catch (\Exception $e) {

                switch ($e->getCode()) {

                    case 23000:
                        $message = "Le nom d'utilisateur existe déjà ! ";
                        break;

                    default:
                        $message = "Une erreur est survenue ! erreur " . $e->getMessage();

                }
            }
            $this->App->session()->setFlash($message);

        }
        $this->page->addVar('Form', $Form->createView());

    }

}