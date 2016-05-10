<?php
namespace App\Backend;

use \OCFram\Application;

class BackendApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Backend');

    }

    public function run()
    {
        if ($this->Session->isAuthenticated()) {
            $Controller = $this->getController();
        } else {
            $Controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }

        $Controller->execute();

        $this->HttpResponse->setPage($Controller->page());
        $this->HttpResponse->send();

    }
}