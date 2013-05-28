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

    public function userAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            if(isset($_POST['id'])){
                $id=$_POST['id'];
                $em = Zend_Registry::get("doctrine")->getEntityManager();
                $user = $em->getRepository('\Dtad\Entity\User')->findOneById($id);
                $profile = $em->getRepository('\Dtad\Entity\Profile')->findOneByUser($id);
                if($user == null or $profile == null)
                    $this->_helper->json(array("success"=>false));
                $response = array(
                                "success"=>true,
                                "firstname"=>$profile->getFirstName(),
                                "middlename"=>$profile->getMiddleName(),
                                "lastname"=>$profile->getLastName(),
                                "username"=>$user->getusername());
                $this->_helper->json($response);
            }
            else
            {
                $this->_helper->json(array("success"=>false));
            }
        }
        catch(Exception $e)
        {
            $this->_helper->json(array("success"=>false));
        }
    }

    public function catalogAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            if(isset($_POST['id'])){
                $id=$_POST['id'];
                $em = Zend_Registry::get("doctrine")->getEntityManager();
                $activities = $em->getRepository('\Dtad\Entity\Activity')->findByUser($id);
                $activities_array = array();
                foreach($activities as $a)
                    array_push($activities_array,
                            array(
                                "id"=>$a->getId(),
                                "name"=>$a->getName(),
                                "description"=>$a->getDescription()
                            )
                    );
                $this->_helper->json(array("success"=>true,"activities"=>$activities_array));
            }
            else
            {
                $this->_helper->json(array("success"=>false));
            }
        }
        catch(Exception $e)
        {
            $this->_helper->json(array("success"=>false));
        }
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
                $response= array('success'=>'true','user_id'=>$result->getIdentity()->userId);
            }
            	else{
            		$response= array('success'=>'false','user_id'=>'-1');
            	}
            $this->_helper->json($response);
    	}
    }

    public function getactivitiesAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        if(isset($_POST['user_id']))
        {
            $user=$em->getRepository('\Dtad\Entity\Activity')->findOneBy($_POST['user_id']);
        }
        else
        {
            $this->_helper->json(array('success'=>'false','activities'=>array()));
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

