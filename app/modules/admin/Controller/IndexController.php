<?php

namespace Admin\Controller;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
    }

    public function loginAction()
    {
        $form = new \Admin\Form\LoginForm();
        $this->view->setVar('form', $form);

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                // perform login attempt
            }

            // never send the password back, regardless of validity
            $form->clear(['password']);
        }
    }

    public function logoutAction()
    {
        $this->session->set('auth', null);
        $this->session->destroy();
        return $this->response->redirect(['for' => \Admin\Routes::INDEX_LOGIN])->send();
    }

    public function error500Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
        $this->tag->prependTitle('Error - ');
    }

    public function error404Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(404);
        $this->tag->prependTitle('Not found - ');
    }
}
