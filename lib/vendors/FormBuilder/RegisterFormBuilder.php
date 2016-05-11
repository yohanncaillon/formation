<?php
namespace FormBuilder;

use OCFram\EmailValidator;
use \OCFram\FormBuilder;
use OCFram\PasswordConfirmValidator;
use OCFram\PasswordField;
use \OCFram\StringField;
use \OCFram\EmailField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class RegisterFormBuilder extends FormBuilder
{

    public function build()
    {

        $this->Form->add(new StringField([
            'label' => 'Pseudo',
            'name' => 'name',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le titre de la news'),
            ],

        ]))->add(new EmailField([
            'label' => 'E-mail',
            'name' => 'email',
            'maxLength' => 200,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le titre de la news'),
                new EmailValidator("Ceci n'est pas un E-mail valide"),
            ],

        ]));

        $PasswordField = new PasswordField([
            'label' => 'Mot de passe',
            'name' => 'password',
            'maxLength' => 200,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier un mot de passe')
            ],

        ]);

        $PasswordConfirmField = new PasswordField([
            'label' => 'Confirmez votre mot de passe',
            'name' => 'password_confirm',
            'maxLength' => 200,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de specifier le mot de passe de confirmation'),
                new PasswordConfirmValidator("Les mots de passe entrés ne sont pas les mêmes", $PasswordField)
            ],

        ]);
        $this->Form->add($PasswordField);
        $this->Form->add($PasswordConfirmField);

    }

}