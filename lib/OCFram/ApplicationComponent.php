<?php
namespace OCFram;

abstract class ApplicationComponent
{
    protected $App;

    public function __construct(Application $App)
    {

        $this->App = $App;
    }

    public function App()
    {
        return $this->App;
    }
}