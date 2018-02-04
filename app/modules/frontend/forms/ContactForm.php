<?php

namespace Frontend\Form;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\TextArea;


class ContactForm extends Form
{
    public function initialize ()
    {

        $email = new Text('email', [
            'placeholder' => 'Email (optional)',
        ]);

        $message = new TextArea('message', [
            'placeholder' => 'Your Message',
        ]);

        $submit = new Submit('submit', [
            'value' => 'Submit',
        ]);

        $this->add($email);
        $this->add($message);
        $this->add($submit);
    }
}
