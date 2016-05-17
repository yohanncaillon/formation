<?php
/**
 * Created by PhpStorm.
 * User: ycaillon-morisseau
 * Date: 17/05/2016
 * Time: 09:49
 */

namespace OCFram;


class ExistsValidator extends Validator
{
    protected $manager;
    protected $method;

    public function __construct($errorMessage, $manager, $method)
    {
        parent::__construct($errorMessage);
        $this->manager = $manager;
        $this->method = $method;

    }

    public function isValid($value)
    {
        $m = $this->method;
        return !$this->manager->$m($value);
    }
}