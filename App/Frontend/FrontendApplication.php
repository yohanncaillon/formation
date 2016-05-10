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

        $this->httpResponse->setPage($Controller->page());
        $this->httpResponse->send();

    }
}