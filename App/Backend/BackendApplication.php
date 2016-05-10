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
        if ($this->session->isAuthenticated()) {
            $Controller = $this->getController();
        } else {
            $Controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }

        $Controller->execute();

        $this->httpResponse->setPage($Controller->page());
        $this->httpResponse->send();

    }
}