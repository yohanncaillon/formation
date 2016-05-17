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

    public function isValid($value)
    {
        return !preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value);

    }
}