<?php

namespace Admin\Form;

use \Component\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{
    protected function addFields ():void
    {
        $email = new Text('email', [
            'placeholder' => 'Email',
        ]);
        $email->addValidator(new Email([
            'allowEmpty' => false,
            'message' => 'Not a valid email address',
        ]));

        $password = new Password('password', [
            'placeholder' => 'Password',
        ]);
        $password->addValidator(new PresenceOf([
            'message' => 'Password is required',
        ]));

        $submit = new Submit('submit', [
            'value' => 'Submit',
        ]);

        $this->add($email);
        $this->add($password);
        $this->add($submit);
    }
}
