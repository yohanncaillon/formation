<?php
namespace App\Frontend;

use \OCFram\Application;

class FrontendApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Frontend');

    }

    public function run()
    {
        $Controller = $this->getController();
        $Controller->execute();

        $this->HttpResponse->setPage($Controller->page());
        $this->HttpResponse->send();

    }
}