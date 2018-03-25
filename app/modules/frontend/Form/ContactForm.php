<?php

namespace Frontend\Form;

use \Component\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class ContactForm extends Form
{
    protected function addFields ():void
    {
        $email = new Text('email', [
            'placeholder' => 'Email (optional)',
        ]);
        $email->addValidator(new Email([
            'allowEmpty' => true,
            'message' => 'Not a valid email address',
        ]));

        $message = new TextArea('message', [
            'placeholder' => 'Your Message',
        ]);
        $message->addValidator(new PresenceOf([
            'message' => 'Please enter a message',
        ]));

        $submit = new Submit('submit', [
            'value' => 'Submit',
        ]);

        $this->add($email);
        $this->add($message);
        $this->add($submit);
    }
}
