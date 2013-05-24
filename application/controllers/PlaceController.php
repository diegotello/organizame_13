<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class PlaceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = new Dtad_Form_Place();
        $this->view->form = $form;
            if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if($form->isValid($data))
                {    
                    $em = Zend_Registry::get("doctrine")->getEntityManager();
                    
                    //new place
                    $place= new \Dtad\Entity\Place;
                    $place->setDescription($data['description']);
                    $place->setAddress($data['firstaddress']);
                    $place->setSecondAddress($data['secondaddress']);
                    $place->setLat($data['lat']);
                    $place->setLong($data['long']);
                    $auth = Zend_Auth::getInstance();
                    if ($auth->hasIdentity())
                    {
                        $place->setUser($em->getRepository('\Dtad\Entity\User')->findOneById($auth->getIdentity()->userId));
                        $em->persist($place);
                        $em->flush();
                        $this->_redirect('/place');
                    }
                    else
                    {
                        $this->view->errorMessage="No se encuentra la informacion del usuario conectado.";
                    }
                }
            }
    }
    
    public function getgridplacesbyuserAction(){
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        $result=execQuery("
                    SELECT p.id, p.description, p.address, p.second_address
                    FROM place as p
                    WHERE p.user_id=".Zend_Auth::getInstance()->getIdentity()->userId." 
                    ORDER BY p.id");
        $rows=array();
        if(isset($_POST['rp']))
            $rp=$_POST['rp'];
        else
            $rp=-1;
        if(isset($_POST['page']))
            $page=$_POST['page'];
        else
            $page=1;
        $i=0;
        foreach($result as $ac){
            $ac['num']=$i;
            $ac['fulladdress']=$ac['address'].' / '.$ac['second_address'];
            if($rp==-1)
            {
                array_push($rows,array('id'=>$ac['id'],'cell'=>$ac));
            }
            else
            {
                if($i >= $page*$rp)
                    break;
                else
                    if($i>=$rp*($page-1))
                        array_push($rows,array('id'=>$ac['id'],'cell'=>$ac));
            }
            $i+=1;
        }
        $this->_helper->json(array('page'=>$page,"rows"=>$rows,"total"=>sizeof($result)));
    }
}