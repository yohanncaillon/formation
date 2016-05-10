<?php
/**
 * Created by PhpStorm.
 * User: ycaillon-morisseau
 * Date: 10/05/2016
 * Time: 10:10
 */

namespace OCFram;


class EmailValidator extends Validator
{

    public function isValid($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}