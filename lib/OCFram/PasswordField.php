<?php

namespace OCFram;


class PasswordField extends Field
{

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        $widget .= '<div class="form-group"><label>' . $this->label . '</label><input class="form-control" type="password" name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        return $widget .= ' /></div>';
    }

}