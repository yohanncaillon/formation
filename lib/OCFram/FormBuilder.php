<?php
namespace OCFram;

abstract class FormBuilder
{
    protected $Form;

    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }

    abstract public function build();

    public function setForm(Form $Form)
    {
        $this->Form = $Form;
    }

    public function form()
    {
        return $this->Form;
    }
}