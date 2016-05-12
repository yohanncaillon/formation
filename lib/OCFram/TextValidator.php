<?php
/**
 * Created by PhpStorm.
 * User: ycaillon-morisseau
 * Date: 11/05/2016
 * Time: 16:07
 */

namespace OCFram;


class TextValidator extends Validator
{

    protected $specialChars;

    public function __construct($errorMessage, $specialChars)
    {
        parent::__construct($errorMessage);

        $this->specialChars = $specialChars;

    }

    public function isValid($value)
    {
        return preg_match('/^[a-zA-Z0-9éèêë' . $this->specialChars . ']+$/', $value);
    }
}