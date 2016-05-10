<?php
namespace OCFram;

abstract class Field
{
    use Hydrator;

    protected $errorMessage;
    protected $label;
    protected $name;
    protected $validator_a = [];
    protected $value;
    protected $saveValue = true;

    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->hydrate($options);
        }
    }

    abstract public function buildWidget();

    public function isValid()
    {
        foreach ($this->validator_a as $validator) {
            if (!$validator->isValid($this->value)) {
                $this->errorMessage = $validator->errorMessage();
                return false;
            }
        }

        return true;
    }

    public function label()
    {
        return $this->label;
    }

    public function length()
    {
        return $this->length;
    }

    public function name()
    {
        return $this->name;
    }

    public function saveValue()
    {
        return $this->saveValue;
    }
    public function setSaveValue($value)
    {
        $this->saveValue = $value;
    }

    public function validators()
    {
        return $this->validator_a;
    }

    public function value()
    {
        return $this->value;
    }

    public function setLabel($label)
    {
        if (is_string($label)) {
            $this->label = $label;
        }
    }

    public function setLength($length)
    {
        $length = (int)$length;

        if ($length > 0) {
            $this->length = $length;
        }
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        }
    }

    public function setValidators(array $validator_a)
    {
        foreach ($validator_a as $validator) {
            if ($validator instanceof Validator && !in_array($validator, $this->validator_a)) {
                $this->validator_a[] = $validator;
            }
        }
    }

    public function setValue($value)
    {
        if (is_string($value)) {
            $this->value = $value;
        }
    }
}