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

    public function updatetaskAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if(isset($data['id'])&&isset($data['name']))
                {
                    $id = $data['id'];
                    $name=$data['name'];
                    $description=$data['description'];
                    $em = Zend_Registry::get("doctrine")->getEntityManager();
                    $activity = $em->getRepository('\Dtad\Entity\Activity')->findOneById($id);
                    $activity->setName($name);
                    $activity->setDescription($description);
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

    public function getactivityobservationsAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        try
        {
            $now = new dateTime();
            if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if(isset($data['id']))
                {    
                    $id = $data['id'];
                    $measures=execQuery("
                            SELECT (end-start)/60 as obs
                            FROM measure
                            WHERE activity_id=".$id."
                        ");
                    $result_data = array(array('x'=>0,'y'=>0));
                    $i=1;
                    foreach($measures as $me)
                    {
                        array_push($result_data,array('x'=>$i,'y'=>round($me['obs'],1)));
                        $i+=1;
                    }
                    $this->_helper->json(array("success"=>true,'data'=>$result_data));
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

    public function addactivitytoplanAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $id = $this->_getParam('id');
            $uid = $this->_getParam('user_id');            
            $day = date("d-m-y");
            $start = date("H:i:s");
            execQuery("INSERT INTO cronogram(user_id,activity_id,day,start,status) VALUES (".$uid.",".$id.",'".$day."','".$start."','todo')");
            $this->_helper->json(array("success"=>true));
        }
    }

    public function gettodayactivitiesAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->_getParam('id');
        $result = execQuery("
                    SELECT a.id, a.name, a.description, c.start, at.name atname, c.id as cid
                    FROM cronogram as c, activity as a, activitytype as at
                    WHERE c.day='".date("d-m-y")."' 
                          AND c.user_id=".$id."
                          AND c.activity_id=a.id
                          AND a.activitytype_id=at.id
                    ORDER BY c.start
                 ");
        $result2 = array();
        foreach($result as $r)
        {
            $r['atname']=getActivityType_TypeName($r['atname'])." / ".getActivityType_RegisterName($r['atname']);
            array_push($result2,$r);            
        }
        $this->_helper->json(array("success"=>true,"activities"=>$result2));
    }

    public function startmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $id = $this->_getParam('id');
            $start = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("INSERT INTO measure(cronogram_id,activity_id,start,end,status) VALUES (".$cid.",".$id.",".strtotime($start).",".strtotime($start).",'on_measure')")=== false
            )
                $this->_helper->json(array('success'=>false));
            else
            {
                execQuery("UPDATE cronogram SET status='doing' WHERE id=".$cid);
                $this->_helper->json(array('success'=>true));
            }
        }
    }

    public function endmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $id = $this->_getParam('id');
            $end = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("UPDATE measure SET end = ".strtotime($end).",status = 'done' WHERE activity_id=".$id." AND cronogram_id=".$cid." AND status='on_measure'")=== false
            )
                $this->_helper->json(array('success'=>false));
            else
            {
                execQuery("UPDATE cronogram SET status='done' WHERE id=".$cid);
                $this->_helper->json(array('sucess'=>true));
            }
        }
    }

}