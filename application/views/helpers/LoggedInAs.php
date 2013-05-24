<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $username = $identity->username;

            $logoutUrl = $this->view->url(array('controller'=>'auth',
                                                'action'=>'logout'), 'logout');
            $control = <<<CONTROL
<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
    <i class="icon-user"></i> {$username} <span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li><a href="{$logoutUrl}">Cerrar Sesion</a></li>
</ul>
CONTROL;
            return $control;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'), 'login');
        return '<a class="btn btn-primary" href="'.$loginUrl.'"><i class="icon-user icon-white"></i>
            Iniciar Sesion</a>';
    }
}
