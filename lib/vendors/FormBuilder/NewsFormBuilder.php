<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class NewsFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->Form->add(new StringField([
                'label' => 'Titre',
                'name' => 'titre',
                'maxLength' => 100,
                'validators' => [
                    new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier le titre de la news'),
                ],
            ]))
            ->add(new TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new NotNullValidator('Merci de spécifier le contenu de la news'),
                ],
            ]))->add(new StringField([
                'label' => 'Tags (à séparer avec des virgules)',
                'name' => 'tagString',
                'maxLength' => 300,
                'validators' => [
                    new MaxLengthValidator('Les tags spécifié sont trop long (100 caractères maximum)', 300),
                    new NotNullValidator('Merci de spécifier les tags de la news'),
                ],
            ]));
    }
}