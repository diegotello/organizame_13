<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('layout');
    }

    public function indexAction()
    {
        // action body
    }

    public function mainAction()
    {
        $past_accordion="<div id=past_accordion>";
        $present_accordion="<div id=present_accordion>";
        $future_accordion="<div id=future_accordion>";
        $cat_char1="[";
        $est_char1="[";
        $real_char1="[";
        $est_char2="[";
        $real_char2="[";
        $est_acumulado=0;
        $real_acumulado=0;
        $today_activities = gettodayactivities();
        foreach($today_activities as $act)
        {
            $name=$act['name'];
            $typereg=$act['atname'];
            $start=$act['start'];
            $cid=$act['cid'];
            $id=$act['id'];
            $description=$act['description'];
            $status=$act['status'];            
            $act_div="
                        <div class=group id=".$cid."_group>
                            <h3>".$name." / ".$typereg." / ".$start."</h3>
                            <div id=".$cid."_group_div>
                                <h4>Descripcion: ".$description."</h4>
                                <div id=".$cid."_activity_container class=container-fluid>
                                    <div class=row-fluid>
                                        <div id=".$cid."_error class=span12>
                                        </div>
                                    </div>";
            if($status=='todo')
                $act_div.="         <div class=row-fluid>
                                        <div align=center class=span4>
                                            <button type=button class='btn btn-danger' onclick=removeActivity(".$cid.")>Quitar Actividad</button>
                                        </div>
                                    </div>";
            $act_div.=               "<div class=row-fluid>";
            if($status=='todo')
                $act_div.=             "<div class=span4>
                                            <div class=span12 align=center>
                                                <label><h5>Hora Deseada</h5></label>
                                            </div>
                                            <div class=span12 align=center>
                                                <select id=".$cid."_start_hour class=input-mini>".getSelectHours()."</select>
                                                <select id=".$cid."_start_minute class=input-mini>".getSelectMinutes()."</select>
                                            </div>
                                            <div class=span12 align=center>
                                                <button type=button class='btn btn-primary' onclick=changeTime(".$cid.")>Reprogramar</button>
                                            </div>
                                        </div>";
            if(strpos($typereg,"Temporal")!==false)
            {
                $measure = measureStatus($cid,$id,$start);
                $startMvalue = "Inicio";
                $endMvalue = "Fin";
                $header = "Registro Temporal";
                $button = "<button id=".$cid."_start_time_count_btn type=button class='btn btn-primary' onclick=startActivity(".$cid.",".$id.")>Iniciar</button>";
                if($cat_char1==='[')
                    $cat_char1.="'".$name."'";
                else
                    $cat_char1.=",'".$name."'";
                if($est_char1==='[')
                    $est_char1.=getEstimated($id);
                else
                    $est_char1.=",".getEstimated($id);
                if($est_char2==='[')
                    $est_char2.=($est_acumulado+getEstimated($id));
                else
                    $est_char2.=",".($est_acumulado+getEstimated($id));
                $est_acumulado+=getEstimated($id);                
                if($measure!==false)
                {
                    switch($measure['status'])
                    { 
                        case "done":
                            $startMvalue=$measure['start'];
                            $endMvalue=$measure['end'];
                            $button = "<button id=".$cid."_restart_time_count_btn type=button class='btn btn-inverse' onclick=restartActivity(".$cid.",".$id.")>Reiniciar</button>";
                            $header = "Completado en ".$measure['time']." minutos";
                            $info="";
                            if($real_char1==='[')
                                $real_char1.=$measure['time'];
                            else
                                $real_char1.=",".$measure['time'];
                            if($real_char2==='[')
                                $real_char2.=$real_acumulado+$measure['time'];
                            else
                                $real_char2.=",".($real_acumulado+$measure['time']);
                            $real_acumulado+=$measure['time'];
                            break;
                        case "on_measure":
                            $startMvalue=$measure['start'];
                            $button="<button id=".$cid."_end_time_count_btn type=button class='btn btn-danger' onclick=endActivity(".$cid.",".$id.")>Finalizar</button>";
                            $info="<div class=span12 align=center>
                                    <input type=text id=".$cid."_start_measure value=".$startMvalue." class=input-mini readonly=readonly>
                                    <input type=text id=".$cid."_end_measure value=".$endMvalue." class=input-mini readonly=readonly>
                                   </div>";
                            break;
                    }
                }
                else
                {
                    $info="<div class=span12 align=center>
                            <input type=text id=".$cid."_start_measure value=".$startMvalue." class=input-mini readonly=readonly>
                            <input type=text id=".$cid."_end_measure value=".$endMvalue." class=input-mini readonly=readonly>
                           </div>";
               }
                $act_div.=            "<div class=span4 id=".$cid."_time_register>
                                            <div class=span12 align=center>
                                                <label><h5>".$header."</h5></label>
                                            </div>                                            
                                            ".$info."
                                            <div class=span12 align=center>
                                            ".$button."
                                            </div>
                                        </div>";
            }
            if(strpos($typereg,"Contable")!==false)
            $act_div.=                  "<div class=span4 id=".$cid."_count_register>
                                            <div class=span12 align=center>
                                                <label><h5>Registro Contable</h5></label>
                                            </div>
                                            <div class=span12 align=center>
                                                <input type=text id=".$cid."_modify_count value=Cuenta class=input-medium>
                                            </div>
                                            <div class=span12 align=center>
                                                <button id=".$cid."_add_count_btn type=button class='btn btn-primary' onclick=addCount(".$cid.",".$id.")>Sumar</button>
                                                <button id=".$cid."_sub_count_btn type=button class='btn btn-danger' onclick=subCount(".$cid.",".$id.")>Restar</button>
                                            </div>
                                        </div>";
            $act_div.="             </div>
                                </div>";
            if(strpos($typereg,"Bloque")!==false)
            {
                $act_div.="     <div id=sub_accordion>
                                    <div id=".$id."_accordion_group class=group>";
                $block_activities=getblockactivities($id);
                foreach($block_activities as $bact)
                {
                    $bid=$bact['id'];
                    $bname=$bact['name'];
                    $bdescription=$bact['description'];
                    $btypereg=getActivityType_TypeName($bact['atname'])." / ".getActivityType_RegisterName($bact['atname']);
                    $order=$bact['start'];                    
                    $act_div.="         <h3>Tarea ".$order.": ".$bname."</h3>
                                        <div id=".$cid."_activity_container_".$bid.">
                                            <div class=row-fluid>";
                    if(strpos($btypereg,"Temporal")!==false && $status==='doing')
                    {
                        $bmeasure=measureStatus($cid,$bid,$start);
                        $bstartMvalue = "Inicio";
                        $bendMvalue = "Fin";
                        $bheader = "Registro Temporal";
                        $bbutton = "<button id=".$cid."_start_time_count_".$bid."_btn type=button class='btn btn-primary' onclick=startbActivity(".$cid.",".$bid.")>Iniciar</button>";
                        if($bmeasure!==false)
                        {
                            switch($bmeasure['status'])
                            { 
                                case "done":
                                    $bstartMvalue=$bmeasure['start'];
                                    $bendMvalue=$bmeasure['end'];
                                    $bbutton = "<button id=".$cid."_restart_time_count_btn type=button class='btn btn-inverse' onclick=restartbActivity(".$cid.",".$bid.")>Reiniciar</button>";
                                    $bheader = "Completado en ".$bmeasure['time']." minutos";
                                    $binfo="";
                                    break;
                                case "on_measure":
                                    $bstartMvalue=$bmeasure['start'];
                                    $bbutton="<button id=".$cid."_end_time_count_btn type=button class='btn btn-danger' onclick=endbActivity(".$cid.",".$bid.")>Finalizar</button>";
                                    $binfo="<div class=span12 align=center>
                                            <input type=text id=".$cid."_start_measure_".$bid." value=".$bstartMvalue." class=input-mini readonly=readonly>
                                            <input type=text id=".$cid."_end_measure_".$bid."  value=".$bendMvalue." class=input-mini readonly=readonly>
                                           </div>";
                                    break;
                            }
                        }
                        else
                        {
                            $binfo="<div class=span12 align=center>
                                    <input type=text id=".$cid."_start_measure_".$bid." value=".$bstartMvalue." class=input-mini readonly=readonly>
                                    <input type=text id=".$cid."_end_measure_".$bid." value=".$bendMvalue." class=input-mini readonly=readonly>
                                   </div>";
                       }
                        $act_div.=         "    <div class=span4 id=".$cid."_time_register_".$bid.">
                                                    <div class=span12 align=center>
                                                        <label><h5>".$bheader."</h5></label>
                                                    </div>                                            
                                                    ".$binfo."
                                                    <div class=span12 align=center>
                                                    ".$bbutton."
                                                    </div>
                                                </div>";
                    }
                    if(strpos($btypereg,"Contable")!==false && $status==='doing')
                    $act_div.=                  "<div class=span6 id=".$cid."_count_register_".$bid.">
                                                    <div class=span12 align=center>
                                                        <label><h5>Registro Contable</h5></label>
                                                    </div>
                                                    <div class=span12 align=center>
                                                        <input type=text id=".$cid."_modify_count_".$bid." value=Cuenta class=input-medium>
                                                    </div>
                                                    <div class=span12 align=center>
                                                        <button id=".$cid."_add_count_".$bid."_btn type=button class='btn btn-primary' onclick=addBlockCount(".$cid.",".$bid.")>Sumar</button>
                                                        <button id=".$cid."_sub_count_".$bid."_btn type=button class='btn btn-primary' onclick=subBlockCount(".$cid.",".$bid.")>Restar</button>
                                                    </div>
                                                </div>";                        
                    $act_div.="             </div>
                                        </div>";
                }
                $act_div.="         </div>
                                </div>";
            }
            if(strpos($typereg,"Ruta")!==false)
            {
                $act_div.="     <div id=sub_accordion>
                                    <div id=".$id."_accordion_group class=group>";
                $route_activities=getrouteactivities($id);
                foreach($route_activities as $ract)
                {                    
                    $rdescription=$ract['description'];
                    $address=$ract['address'];
                    $rid=$ract['id'];
                    $act_div.="         <h3>Lugar: ".$rdescription." / ".$address."</h3>
                                        <div id=".$cid."_route_container_".$rid.">";
                    if($status==='doing')
                        $act_div.=         "<div class=row-fluid>
                                                <div class=span5 id=".$cid."_route_register_".$rid.">
                                                    <div class=span12 align=center>
                                                        <label><h5>Registra al pasar por este lugar</h5></label>
                                                    </div>
                                                    <div class=span12 align=center>
                                                        <button id=".$cid."_checkpoint_".$rid."_btn type=button class='btn btn-inverse' onclick=checkpoint(".$cid.",".$rid.")>Superado</button>
                                                    </div>
                                                </div>
                                            </div>";
                    $act_div.=          "</div>";
                }
                $act_div.="         </div>
                                </div>";
            }
            $act_div.=      "</div>
                        </div>";
            switch($status)
            {
                case 'done':
                    $past_accordion.=$act_div;
                    break;
                case 'doing':
                    $present_accordion.=$act_div;
                    break;
                case 'todo':
                    $future_accordion.=$act_div;
                    break;
            }            
        }
        $cat_char1.="]";
        $est_char1.="]";
        $real_char1.="]";
        $est_char2.="]";
        $real_char2.="]";
        $past_accordion.="</div>";
        $present_accordion.="</div>";
        $future_accordion.="</div>";
        $this->view->real2=$real_char2;
        $this->view->estimado2=$est_char2;
        $this->view->real1=$real_char1;
        $this->view->estimado1=$est_char1;
        $this->view->categories1=$cat_char1;
        $this->view->past_accordion=$past_accordion;
        $this->view->present_accordion=$present_accordion;
        $this->view->future_accordion=$future_accordion;
    }
}