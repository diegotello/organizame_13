<?php

class AuthController extends Zend_Controller_Action
{

     public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('layout');
    }

    public function indexAction()
    {
        $this->_forward('login');
    }

     public function loginAction()
    {
        $form = new Dtad_Form_Login();
        $request = $this->getRequest();
        if ($request->isPost())
        {
            if ($form->isValid($request->getPost()))
            {
                if ($this->_authenticate($form->getValues()))
                {
                    $role = Zend_Auth::getInstance()->getIdentity()->role;
                    $this->_redirect('/home');                    
                }
                else
                {
                    $this->view->alert = "
                    <div class='alert alert-danger'>
                        <button type=button class=close data-dismiss=alert>x</button>
                        <strong>Invalid User or Password</strong>
                    </div>";
                }
            }
        }

        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index'); // back to login page
    }

    private function _authenticate($values)
    {
        $adapter = new Dtad_Auth_Adapter($values['username'], $values['password']);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $user = $result->getIdentity();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }
}

