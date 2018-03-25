<?php
namespace Component;

use Phalcon\Forms\Element\Hidden;

abstract class Form extends \Phalcon\Forms\Form
{
    final public function initialize ()
    {
        $csrf = new Hidden($this->getCsrfTokenKey(), [
            'value' => $this->getCsrfToken(),
        ]);

        $this->add($csrf);
        $this->addFields();
    }

    abstract protected function addFields():void;

    public function getCsrfTokenKey ():string
    {
        $tokenKeyId = '$PHALCON/CSRF/KEY$';

        if ($this->session->has($tokenKeyId)) {
            return $this->session->get($tokenKeyId);
        }

        return $this->security->getTokenKey();
    }

    public function getCsrfToken ():string
    {
        return $this->security->getSessionToken() ? : $this->security->getToken();
    }
}
