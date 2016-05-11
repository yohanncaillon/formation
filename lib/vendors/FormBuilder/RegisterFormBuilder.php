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
use \Entity\User;

class RegisterFormBuilder extends FormBuilder
{

    protected $password_confirm;
    protected $User;

    public function __construct(User $entity, $password_confirm)
    {
        parent::__construct($entity);
        $this->User = $entity;
        $this->password_confirm = $password_confirm;

    }

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
            'value' => $this->password_confirm,
            'saveValue' => false,
            'maxLength' => 200,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier un mot de passe'),
                new PasswordConfirmValidator("Les mots de passe entrés ne sont pas les mêmes", $this->User)
            ],

        ]);
        $this->Form->add($PasswordField);
        $this->Form->add($PasswordConfirmField);

    }

}