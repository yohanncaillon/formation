<?php
namespace OCFram;

class FormHandler
{
    protected $Form;
    protected $Manager;
    protected $Request;

    public function __construct(Form $form, Manager $manager, HTTPRequest $request)
    {
        $this->setForm($form);
        $this->setManager($manager);
        $this->setRequest($request);
    }

    public function process()
    {
        if ($this->Request->method() == 'POST' && $this->Form->isValid()) {
            $this->Manager->save($this->Form->entity());

            return true;
        }

        return false;
    }

    public function setForm(Form $Form)
    {
        $this->Form = $Form;
    }

    public function setManager(Manager $Manager)
    {
        $this->Manager = $Manager;
    }

    public function setRequest(HTTPRequest $Request)
    {
        $this->Request = $Request;
    }
}