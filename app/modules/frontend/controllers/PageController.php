<?php

namespace Frontend\Controllers;

class PageController extends ControllerBase
{
    public function showAction()
    {
        $page = $this->dispatcher->getParam('page', 'string');
        $view = sprintf('page/%s', $page);

        if ($this->view->exists($view)) {
            $this->view->pick($view);
            $this->tag->prependTitle(sprintf('%s - ', ucfirst($page)));
            return true;
        }

        // if content does not exist, show 404
        throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
    }

    public function contactAction()
    {
        if ($this->dispatcher->hasParam('success')) {
            $this->view->pick('page/contact-success');
            return;
        }

        $form = new \Frontend\Forms\ContactForm();
        $this->view->setVar('form', $form);

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {

                // TODO: implement sending of email

                // by redirecting we get the browser to clear the POST data
                // and can change to a success view
                return $this->response->redirect('contact/success')->send();
            }
        }
    }
}
