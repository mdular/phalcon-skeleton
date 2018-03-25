<?php

namespace Frontend\Controller;

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
        // show success view if applicable
        if ($this->dispatcher->hasParam('success')) {
            $this->view->pick('page/contact-success');
            return;
        }

        $form = new \Frontend\Form\ContactForm();
        $this->view->setVar('form', $form);

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {

                // get message config
                $config = $this->config->message->contact;

                // create message body from form input
                $body = sprintf(
                    (string) $config->bodyTemplate,
                    $this->request->getPost('email', 'email'),
                    $this->request->getPost('message', 'string'));

                $message = $this->mailer->createMessage()
                    ->to($config->recipient, $config->name)
                    ->subject($config->subject)
                    ->content($body, \Phalcon\Mailer\Message::CONTENT_TYPE_PLAIN);

                // send message
                $message->send();

                // write to log
                $this->logger->info(sprintf('Contact mail sent: %s', $body));

                // by redirecting we get the browser to clear the POST data
                // we add the success param to change to a success view
                return $this->response->redirect('contact/success')->send();
            }
        }
    }
}
