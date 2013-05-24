<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
         
    }

    public function indexAction()
    {
        // action body
        
    }

    public function createAction()
    {
        // action body
        $form = new Dtad_Form_User();
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
                
                //new place for home
                $place = new \Dtad\Entity\Place;
                $place->setDescription("Mi casa");
                $place->setUser($user);
                $place->setAddress($data['homeaddress']);
                $place->setLat($data['lat']);
                $place->setLong($data['long']);
                $em->persist($place);
                
                //new user profile
                $profile = new \Dtad\Entity\Profile;
                $profile->setUser($user);
                $profile->setMiddleName($data['middlename']);
                $profile->setHome($place);
                $profile->setFirstName($data['firstname']);
                $profile->setLastName($data['lastname']);
                $em->persist($profile);
                $em->flush();
                
                $this->view->message=$this->view->message."<dd><p style='font-size=14pt'>New user has been created.</p></dd>";
                $this->_redirect('home');
            }
        }
    }

}





