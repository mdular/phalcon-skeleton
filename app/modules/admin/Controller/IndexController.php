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
                $user = \Admin\Model\AdminUser::findFirst([
                    'conditions' => 'email = ?1',
                    'bind' => [
                        1 => $this->request->getPost('email', 'email'),
                    ],
                ]);

                if ($user) {
                    $password = $this->request->getPost('password', 'string');

                    if ($this->security->checkHash($password, $user->password)) {
                        // The password is valid, set 'auth' on session
                        $this->session->set('auth', [
                            'id' => $user->getId(),
                            'name' => $user->getName(),
                        ]);

                        // redirect to index
                        return $this->response->redirect(['for' => \Admin\Routes::INDEX_INDEX])->send();
                    }
                } else {
                    // To protect against timing attacks. Regardless of whether a user
                    // exists or not, the script will take roughly the same amount as
                    // it will always be computing a hash.
                    $this->security->hash(rand());
                }

                // The validation has failed
                $this->flashSession->error('Login failed.');
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
