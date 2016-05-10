<?php
namespace OCFram;

class Form
{
    protected $Entity;
    protected $field_a = [];

    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }

    public function add(Field $field)
    {

        if($field->saveValue()) {
            $attr = $field->name(); // On récupère le nom du champ.
            $field->setValue($this->Entity->$attr()); // On assigne la valeur correspondante au champ.
        }

        $this->field_a[] = $field; // On ajoute le champ passé en argument à la liste des champs.
        return $this;
    }

    public function createView()
    {
        $view = '';

        // On génère un par un les champs du formulaire.
        foreach ($this->field_a as $field) {
            $view .= $field->buildWidget() . '<br />';
        }

        return $view;
    }

    public function isValid()
    {
        $valid = true;

        // On vérifie que tous les champs sont valides.
        foreach ($this->field_a as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }

        return $valid;
    }

    public function entity()
    {
        return $this->Entity;
    }

    public function setEntity(Entity $Entity)
    {
        $this->Entity = $Entity;
    }
}