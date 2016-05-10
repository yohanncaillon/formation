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

            $user = new User ([
                'name' => $request->postData('name'),
                'password' => $request->postData('password'),
                'status' => 2,
                'email' => $request->postData('email')
            ]);
            $password_confirm = $request->postData('password_confirm');
        } else {

            $user = new User();
            $password_confirm = "";
        }

        $formBuilder = new RegisterFormBuilder($user, $password_confirm);
        $formBuilder->build();
        $form = $formBuilder->form();

        if ($request->method() == 'POST' && $form->isValid()) {

            try {
                $this->managers->getManagerOf('Users')->add($user);
                $this->app()->session()->setAuthenticated(true, $user);
                $this->app->httpResponse()->redirect('/');
            } catch (\Exception $e) {

                switch ($e->getCode()) {

                    case 23000:
                        $message = "Le nom d'utilisateur existe déjà ! ";
                        break;

                    default:
                        $message = "Une erreur est survenue ! erreur " . $e->getMessage();

                }
            }
            $this->app->session()->setFlash($message);

        }
        $this->page->addVar('form', $form->createView());

    }

}