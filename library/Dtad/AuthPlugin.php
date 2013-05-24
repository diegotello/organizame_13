<?php
class Dtad_AuthPlugin extends Zend_Controller_Plugin_Abstract
{
    protected $_auth;
    protected $_acl;
    protected $_action;
    protected $_controller;
    protected $_module;
    protected $_currentRole;

    public function __construct(Zend_Acl $acl, array $options = array())
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = $acl;
    }

   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
        $this->_init($request);

        $resource = $this->_getResource();


        if (!Zend_Controller_Front::getInstance()->getDispatcher()->isDispatchable($request))
        {
            // Route not dispatchable, the ErrorController will handle it.
            return;
        }

        if (!$this->_acl->isAllowed($this->_currentRole, $resource, $this->_action))
        {
            if ($this->_currentRole == 'guest')
            {
                $request->setModuleName('default');
                $request->setControllerName('auth');
                $request->setActionName('index');
            }
            else
            {
                $request->setControllerName('error');
                $request->setActionName('notallowed');
            }
        }
    }

    protected function _getResource()
    {
        $resource = $this->_module.':'.$this->_controller;

        if (!$this->_acl->has($resource))
        {
            $resource = $this->_module.':all';

            if (!$this->_acl->has($resource))
            {
                $resource = null;
            }
        }

        return $resource;
    }

    protected function _init($request)
    {
        $this->_action = $request->getActionName();
        $this->_controller = $request->getControllerName();
        $this->_module = $request->getModuleName();
        $this->_currentRole = $this->_getCurrentUserRole();
    }

    protected function _getCurrentUserRole()
    {
        $role = 'guest';

        if ($this->_auth->hasIdentity())
        {
            $user = $this->_auth->getIdentity();
            if (is_object($user))
            {
                $role = $user->role;
            }
        }

        return $role;
    }
}
