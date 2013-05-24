<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class ActivityController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $form=new Dtad_Form_Activity();
        if($this->getRequest()->isPost())    
            {
                $data = $this->getRequest()->getParams();
                if($form->isValid($data))
                {
                    $name=$data['name'];
                    $description=$data['description'];
                    $activitytype=$data['activitytype1'];
                    if(isset($data['activitytype2']))
                    {
                        $activitytype2=$data['activitytype2'];
                        foreach($activitytype2 as $act)
                            $activitytype.='-'.$act;
                    }
                    $em = Zend_Registry::get("doctrine")->getEntityManager();
                    //new activity
                    $activity= new \Dtad\Entity\Activity;
                    $activity->setName($name);
                    $activity->setDescription($description);
                    $activity->setActivityType($em->getRepository('\Dtad\Entity\ActivityType')->findOneByName($activitytype));
                    $activity->setIsDependent(0);                        
                    $auth = Zend_Auth::getInstance();
                    $identity = $auth->getIdentity();
                    $activity->setUser($em->getRepository('\Dtad\Entity\User')->findOneBy(array('id' => $identity->userId)));
                    $em->persist($activity);
                    $em->flush();
                    $form->getElement('isvalid')->setValue("true");
                    if($data['activitytype1']=="block"){
                        if(isset($_POST['block']) && $_POST['block'][0]!="null")
                        {
                            $i=0;
                            foreach($_POST['block'] as $depactid)
                            {
                                $indep_act = $em->getRepository('\Dtad\Entity\Activity')->findOneById($depactid);
                                $activityDep = new \Dtad\Entity\ActivityDependence;
                                $activityDep->setDependentActivity($activity);
                                $activityDep->setIndependentActivity($indep_act);
                                $activityDep->setOrder($i);
                                $i+=1;
                                $em->persist($activityDep);
                                $em->flush();
                            }
                        }
                    }
                    if($data['activitytype1']=="route"){
                        if(isset($_POST['route']) && $_POST['route'][0]!="null")
                        {
                            $i=0;
                            foreach($_POST['route'] as $id)
                            {
                                $place = $em->getRepository('\Dtad\Entity\Place')->findOneById($id);
                                $placeDep = new \Dtad\Entity\PlaceDependence;
                                $placeDep->setActivity($activity);
                                $placeDep->setPlace($place);
                                $placeDep->setOrder($i);
                                $i+=1;
                                $em->persist($placeDep);
                                $em->flush();   
                            }
                        }
                    }
                    $this->_redirect('activity');
                }
                else
                {
                    $form->getElement('isvalid')->setValue("false");
                }
            }
        $this->view->form=$form;
    }

    public function removeactivityAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $query = execQuery("
                    UPDATE cronogram
                    SET status='deleted'
                    WHERE id=".$id);
            }
            
    }

    public function addactivityAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity())
            {
                $day = date("d-m-y");
                $start = date("H:i:s");
                execQuery("INSERT INTO cronogram(user_id,activity_id,day,start,status) VALUES (".$auth->getIdentity()->userId.",".$id.",'".$day."','".$start."','todo')");
            }
        }
    }

    public function gettodayactivitiesAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $result = execQuery("
                    SELECT a.id, a.name, a.description, c.start, at.name atname, c.id as cid
                    FROM cronogram as c, activity as a, activitytype as at
                    WHERE c.day='".date("d-m-y")."' 
                          AND c.user_id=".Zend_Auth::getInstance()->getIdentity()->userId."
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
        $this->_helper->json($result2);
    }

    public function getgridactivitiesbyuserAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $query = "
                    SELECT a.id, a.name, a.description, at.name as atname
                    FROM activity as a, activitytype as at
                    WHERE a.user_id=".Zend_Auth::getInstance()->getIdentity()->userId." 
                        AND at.id=a.activitytype_id
                    ORDER BY at.name,a.name";
        $result=execQuery($query);
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
            $ac['type']=getActivityType_TypeName($ac['atname']);
            $ac['register']=getActivityType_RegisterName($ac['atname']);
            if(strpos($ac['type'],"Bloque")===false)
            {
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
            }
            $i+=1;
        }
        $this->_helper->json(array('page'=>$page,"rows"=>$rows,"total"=>sizeof($result)));
    }

    public function getgridactivities2byuserAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $query = "
                    SELECT a.id, a.name, a.description, at.name as atname
                    FROM activity as a, activitytype as at
                    WHERE a.user_id=".Zend_Auth::getInstance()->getIdentity()->userId." 
                        AND at.id=a.activitytype_id
                    ORDER BY at.name,a.name";
        $result=execQuery($query);
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
            $ac['type']=getActivityType_TypeName($ac['atname']);
            $ac['register']=getActivityType_RegisterName($ac['atname']);
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

    public function changetimeAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if($this->getRequest()->isXmlHttpRequest())    
            {
                $data = $this->getRequest()->getParams();
                $minutes="";
                $hour="";
                $cid=$data['cid'];
                $valid=false;
                $message="";
                if($data['start_minute']<10)
                    $minutes="0".$data['start_minute'];
                else
                    $minutes=$data['start_minute'];
                if($data['start_hour']<10)
                    $hour="0".$data['start_hour'];
                else
                    $hour=$data['start_hour'];
                $now = date("H:i:s");
                if($now < $hour.":".$minutes.":00")
                    $valid=true;
                else
                {
                    $valid=false;
                    $message="La hora que seleccionaste ya paso, actualmente son las ".$now.", programa tu tarea de nuevo.";
                }
                $result = execQuery("
                                    SELECT id 
                                    FROM cronogram 
                                    WHERE start='".$hour.":".$minutes.":00' 
                                        AND user_id=".Zend_Auth::getInstance()->getIdentity()->userId."
                                        AND day='".date("d-m-y")."'");
                if(sizeof($result)>0)
                {
                    $valid=false;
                    $message.=" Ya hay otra actividad programada a las ".$hour.":".$minutes.":00";
                }
                if($valid)
                {
                    $update=execQuery("UPDATE cronogram SET start='".$hour.":".$minutes.":00' WHERE id=".$cid);
                    if(!$update)
                    {
                        $valid=false;
                        $message="Ups! no pudimos almacenar tus cambios, intentalo de nuevo";
                    }                  
                }
                $this->_helper->json(array('valid'=>$valid,'message'=>$message));
           } 
    }

    public function getactivityAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $activities = execQuery("
                                    SELECT a.id, a.name, a.description 
                                    FROM activity a 
                                    WHERE a.user_id=".$auth->getIdentity()->userId." 
                                            AND (a.activitytype_id=3 OR a.activitytype_id=10 OR a.activitytype_id=11 OR a.activitytype_id=12)");
            $this->_helper->json($activities);
        }
    }

    public function getblockactivitiesAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $query = execQuery("
                    SELECT a.id, a.name, a.description, at.name atname, ad.eorder start
                    FROM activity as a, activitytype as at, activitydependence as ad 
                    WHERE a.activitytype_id=at.id
                        AND ad.dependent_activity_id=".$id."
                        AND a.id=ad.independent_activity_id
                    ORDER BY ad.eorder
                 ");
            $this->_helper->json($query);
        }
    }

    public function getrouteactivitiesAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $query = execQuery("
                    SELECT p.description, CONCAT(CONCAT(p.address,' '),p.second_address) address
                    FROM placedependence as pd, place as p
                    WHERE pd.activity_id=".$id." 
                        AND p.id=pd.place_id
                    ORDER BY pd.eorder
                 ");
            $this->_helper->json($query);
        }
    }

    public function getblockAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $activities = execQuery("
                                    SELECT a.id, a.name, a.description 
                                    FROM activity a 
                                    WHERE a.user_id=".$auth->getIdentity()->userId." 
                                            AND (a.activitytype_id=1 OR a.activitytype_id=4 OR a.activitytype_id=5 OR a.activitytype_id=6)");
            $this->_helper->json($activities);
        }
    }

    public function getrouteAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $activities = execQuery("
                                    SELECT a.id, a.name, a.description 
                                    FROM activity a 
                                    WHERE a.user_id=".$auth->getIdentity()->userId." 
                                            AND (a.activitytype_id=2 OR a.activitytype_id=7 OR a.activitytype_id=8 OR a.activitytype_id=9)");
            $this->_helper->json($activities);
        }
    }

    public function gettimeAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->json(array('time'=>date("H:i:s"),'datetime'=>date("Y-m-d H:i:s")));
    }

    public function startmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $start = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("INSERT INTO measure(cronogram_id,activity_id,start,end,status) VALUES (".$cid.",".$id.",".strtotime($start).",".strtotime($start).",'on_measure')")=== false
            )
                $this->_helper->json(array('isdone'=>false));
            else
            {
                execQuery("UPDATE cronogram SET status='doing' WHERE id=".$cid);
                $this->_helper->json(array('isdone'=>true));
            }
        }
    }

    public function startbmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $start = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("INSERT INTO measure(cronogram_id,activity_id,start,end,status) VALUES (".$cid.",".$id.",".strtotime($start).",".strtotime($start).",'on_measure')")=== false
            )
                $this->_helper->json(array('isdone'=>false));
            else
            {
                execQuery("UPDATE cronogram SET status='doing' WHERE id=".$cid);
                $this->_helper->json(array('isdone'=>true));
            }
        }
    }

    public function endmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $end = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("UPDATE measure SET end = ".strtotime($end).",status = 'done' WHERE activity_id=".$id." AND cronogram_id=".$cid." AND status='on_measure'")=== false
            )
                $this->_helper->json(array('isdone'=>false));
            else
            {
                execQuery("UPDATE cronogram SET status='done' WHERE id=".$cid);
                $this->_helper->json(array('isdone'=>true));
            }
        }
    }

    public function endbmeasureAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $end = date("Y-m-d H:i:s");
            $cid = $this->_getParam('cid');
            if(
                execQuery("UPDATE measure SET end = ".strtotime($end).",status = 'done' WHERE activity_id=".$id." AND cronogram_id=".$cid." AND status='on_measure'")=== false
            )
                $this->_helper->json(array('isdone'=>false));
            else                
                $this->_helper->json(array('isdone'=>true));
        }
    }
    
    public function getchartdataAction()
    {
        $this->_helper->layout()->disableLayout();        
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $media=getEstimated($id);
            $varianza=getVariance($id);
            $measures=execQuery("
                    SELECT (end-start)/60 as obs
                    FROM measure
                    WHERE activity_id=".$id."
                ");
            $categories="[";
            $lsc="[";
            $ls2="[";
            $ls1="[";
            $mean="[";
            $obs="[";
            $li1="[";
            $li2="[";
            $lic="[";
            $i=0;
            foreach($measures as $me)
            {
                if($lsc==="["){
                    $categories.="'".$i."'";
                    $lsc.=($media+3*$varianza);
                    $ls2.=($media+2*$varianza);
                    $ls1.=($media+$varianza);
                    $mean.=$media;
                    $obs.=$me['obs'];
                    $li1.=($media-$varianza);
                    $li2.=($media-2*$varianza);
                    $lic.=($media-3*$varianza);
                }
                else
                {
                    $categories.=",'".$i."'";
                    $lsc.=",".($media+3*$varianza);
                    $ls2.=",".($media+2*$varianza);
                    $ls1.=",".($media+$varianza);
                    $mean.=",".$media;
                    $obs.=",".$me['obs'];
                    $li1.=",".($media-$varianza);
                    $li2.=",".($media-2*$varianza);
                    $lic.=",".($media-3*$varianza);
                }
                $i+=1;
            }
            $categories.="]";
            $lsc.="]";
            $ls2.="]";
            $ls1.="]";
            $mean.="]";
            $obs.="]";
            $li1.="]";
            $li2.="]";
            $lic.="]";
            $this->_helper->json(array(
                                'categories'=>$categories,
                                'lsc'=>$lsc,
                                'ls2'=>$ls2,
                                'ls1'=>$ls1,
                                'mean'=>$mean,
                                'obs'=>$obs,
                                'lic'=>$lic,
                                'li2'=>$li2,
                                'li1'=>$li1,
                            ));
        }
        
    }

    public function listAction()
    {
        $activities = execQuery("
                    SELECT a.id, a.name, a.description, at.name as atname
                    FROM activity as a, activitytype as at
                    WHERE a.user_id=".Zend_Auth::getInstance()->getIdentity()->userId." 
                        AND at.id=a.activitytype_id
                    ORDER BY at.name,a.name");
        $act_list="";
        
        foreach($activities as $act)
        {
            $act_list.=" <li class='well span3'>
                            <b>Actividad </b>".$act['name']."</br>
                            <b>Descripcion </b>".$act['description']."</br>
                            <b>Tipo </b>".getActivityType_TypeName($act['atname'])."</br>
                            <b>Registro </b>".getActivityType_RegisterName($act['atname'])."</br>
                            <a href=list?id=".$act['id']."><button type=button class=btn>Ver</button></a>
                        </li>";
        }
        $this->view->act_list=$act_list;
        if (isset($_GET['id'])){
            $chart="<div class=span12 id=act_chart style=height: 300px>";
            $id = $_GET['id'];
            $media=getEstimated($id);
            $varianza=getVariance($id);
            $measures=execQuery("
                    SELECT (end-start)/60 as obs
                    FROM measure
                    WHERE activity_id=".$id."
                ");
            $categories="[";
            $lsc="[";
            $ls2="[";
            $ls1="[";
            $mean="[";
            $obs="[";
            $li1="[";
            $li2="[";
            $lic="[";
            $i=0;
            foreach($measures as $me)
            {
                if($lsc==="["){
                    $categories.="'".$i."'";
                    $lsc.=($media+3*$varianza);
                    $ls2.=($media+2*$varianza);
                    $ls1.=($media+$varianza);
                    $mean.=$media;
                    $obs.=$me['obs'];
                    $li1.=($media-$varianza);
                    $li2.=($media-2*$varianza);
                    $lic.=($media-3*$varianza);
                }
                else
                {
                    $categories.=",'".$i."'";
                    $lsc.=",".($media+3*$varianza);
                    $ls2.=",".($media+2*$varianza);
                    $ls1.=",".($media+$varianza);
                    $mean.=",".$media;
                    $obs.=",".$me['obs'];
                    $li1.=",".($media-$varianza);
                    $li2.=",".($media-2*$varianza);
                    $lic.=",".($media-3*$varianza);
                }
                $i+=1;
            }
            $categories.="]";
            $lsc.="]";
            $ls2.="]";
            $ls1.="]";
            $mean.="]";
            $obs.="]";
            $li1.="]";
            $li2.="]";
            $lic.="]";
            $chart.="</div>";
            $this->view->chart=$chart;
            $this->view->categories=$categories;
            $this->view->lsc=$lsc;
            $this->view->ls2=$ls2;
            $this->view->ls1=$ls1;
            $this->view->mean=$mean;
            $this->view->obs=$obs;
            $this->view->li1=$li1;
            $this->view->li2=$li2;
            $this->view->lic=$lic;
        }
    }


}



