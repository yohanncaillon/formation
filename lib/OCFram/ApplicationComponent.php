<?php
namespace OCFram;

abstract class ApplicationComponent
{
    protected $App;

    public function __construct(Application $app)
    {

        $this->App = $app;
    }

    public function app()
    {
        return $this->App;
    }
}