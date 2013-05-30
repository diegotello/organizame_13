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
                $response= array('success'=>'true','user_id'=>$result->getIdentity()->userId);
            }
                else{
                    $response= array('success'=>'false','user_id'=>'-1');
                }
            $this->_helper->json($response);
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
                {
                    $aid=$a->getId();
                    $media=getEstimated($aid);
                    $varianza=getVariance($aid);
                    $count=getCount($aid);
                    array_push($activities_array,
                            array(
                                "id"=>$aid,
                                "name"=>$a->getName(),
                                "description"=>$a->getDescription(),
                                "estimate"=>$media,
                                "variance"=>$varianza,
                                "count"=>$count
                            )
                    );
                }
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

    public function createtaskAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            // action body
            $form=new Dtad_Form_ActivityFromService();
            if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if($form->isValid($data))
                {
                    $user_id = $data['user_id'];
                    $name=$data['name'];
                    $description=$data['description'];
                    $estimate=$data['estimate'];
                    $activitytype='individual-time';
                    $em = Zend_Registry::get("doctrine")->getEntityManager();
                    //new activity
                    $activity= new \Dtad\Entity\Activity;
                    $activity->setName($name);
                    $activity->setDescription($description);
                    $activity->setActivityType($em->getRepository('\Dtad\Entity\ActivityType')->findOneByName($activitytype));
                    $activity->setEstimate($estimate);
                    $activity->setIsDependent(0);
                    $activity->setUser($em->getRepository('\Dtad\Entity\User')->findOneById($user_id));
                    $em->persist($activity);
                    $em->flush();
                    $this->_helper->json(array("success"=>true));                   
                }
                else
                {
                    $this->_helper->json(array("success"=>false));
                }
            }
            else
                $this->_helper->json(array("success"=>false));            
        }
        catch(Exception $e)
        {
            $this->_helper->json(array("success"=>false));
        }
    }

    public function createuserAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            // action body
            $form = new Dtad_Form_UserFromService();
            $now = new dateTime();
            $this->view->form = $form;
            if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if($form->isValid($data))
                {    
                    $em = Zend_Registry::get("doctrine")->getEntityManager();
                    
                    $role=$em->getRepository('\Dtad\Entity\Role')->findOneBy(array('id'=>2));
                    //new user
                    $user = new \Dtad\Entity\User;
                    $user->setUserName($data['username']);
                    $user->setEmail($data['email']);
                    $salt = sha1(mt_rand());
                    $user->setSalt($salt);
                    $password = sha1($salt.$data['password']);
                    $user->setPassword($password);
                    $user->setCreatedAt($now);
                    $user->setRole($role);
                    $em->persist($user);
                    
                    //new user profile
                    $profile = new \Dtad\Entity\Profile;
                    $profile->setUser($user);
                    $profile->setMiddleName($data['middlename']);
                    $profile->setFirstName($data['firstname']);
                    $profile->setLastName($data['lastname']);
                    $em->persist($profile);
                    $em->flush();
                    $this->_helper->json(array("success"=>true,'user_id'=>$user->getId()));
                }
                else
                    $this->_helper->json(array("success"=>false));
            }
            else
                $this->_helper->json(array("success"=>false));      
        }
        catch(Exception $e)
        {
            $this->_helper->json(array("success"=>false));
        }
    }
}