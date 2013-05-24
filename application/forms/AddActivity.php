<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class Dtad_Form_AddActivity extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        //settings                
        $this->setAction('/activity/addblockactivities');
        $this->setMethod('post');
        $this->setName('addactivityform');
        $this->setAttrib('class','well span5 form-horizontal');
        
        //Activity type2
        $activityType2 = new Zend_Form_Element_MultiCheckBox("activities");
        $activityType2->setLabel("(Nombre, Descripcion)")    
                     ->setOptions(array('required'=>false))
                     ->addDecorators(array(
                        'ViewHelper',
                        array('Label', array('class' => 'control-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                        ));
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        $result = execQuery("
                    SELECT a.id, a.name, a.description
                    FROM activity as a
                    WHERE a.user_id=".Zend_Auth::getInstance()->getIdentity()->userId);
        $options=array();
        foreach($result as $ac)
        {
            $options[$ac['id']] = $ac['name'].", ".$ac['description'];
        }        
        $activityType2->addMultiOptions($options);
        
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Agregar Actividades")
               ->setOptions(array(
                    'required' => false,
                    'ignore'   => true,
                    'class'    => 'btn btn-primary',
                    'decorators' => array(
                        'ViewHelper',
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-actions')),
                        )
                    ));
                    
        $this->addElements(array(                                
                                $activityType2,
                                $submit
                                ));   
    }


}

