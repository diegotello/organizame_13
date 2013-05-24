<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class WebserviceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
	{
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
		if(isset($_POST['username']) && isset($_POST['password'])){
            $username=$_POST['username'];
            $password=$_POST['password'];

        	$adapter = new Dtad_Auth_Adapter($username, $password);
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $user = $result->getIdentity();
                $auth->getStorage()->write($user);
                $response= array('logged'=>'true');
            }
            	else{
            		$response= array('logged'=>'false');
            	}
            $this->_helper->json($response);
    	}
    }

    public function gettodayactivitiesAction(){
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        $result = execQuery("
                    SELECT a.id, a.name, a.description, at.name atname
                    FROM activity as a, activitytype as at
                    WHERE a.user_id=".Zend_Auth::getInstance()->getIdentity()->userId."
                          AND a.activitytype_id=at.id
                 ");
        $result2 = array();
        foreach($result as $r)
        {
            $r['atname']=getActivityType_TypeName($r['atname']);
            $r['register']=getActivityType_RegisterName($r['atname']);
            array_push($result2,$r);            
        }
        $this->_helper->json($result2);
    }
    	


}

