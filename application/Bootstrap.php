<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRouting()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . "/configs/routes.ini",
            APPLICATION_ENV
        );

        $router = new Zend_Controller_Router_Rewrite();
        $router->addConfig($config, "routes");

        Zend_Controller_Front::getInstance()->setRouter($router);
    }
	
	public function _initAutoloaderNamespaces()
    {
        require_once APPLICATION_PATH . '/../library/vendor/Doctrine/lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');

    }
    
     protected function _initLoadAclConfig()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/acl.ini',
                                      APPLICATION_ENV);
        Zend_Registry::set('acl', $config);
    }

    protected function _initAclControllerPlugin()
    {
        $this->bootstrap('frontcontroller');
        $this->bootstrap('loadAclConfig');

        $front = Zend_Controller_Front::getInstance();
        $acl = new Dtad_Acl();
        $aclPlugin = new Dtad_AuthPlugin($acl);
        $front->registerPlugin($aclPlugin);
    }

}

