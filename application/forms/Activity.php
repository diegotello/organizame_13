<?php

class Dtad_Form_Activity extends Zend_Form
{

    public function init()
    {
        //settings                
        $this->setAction('/activity');
        $this->setMethod('post');
        $this->setName('newactivityform');
        $this->setAttrib('class','well span5 form-horizontal');
        
        //Name
        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Nombre")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
        
        //Description
        $description = new Zend_Form_Element_Text("description");
        $description->setLabel("Descripcion")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
                    
        //Activity type1
        $activityType1 = new Zend_Form_Element_Radio("activitytype1");
        $activityType1->setLabel("Tipo")
                     ->setOptions(array('required'=>true,'onChange'=>'typeChange()'))
                     ->addDecorators(array(
                        'ViewHelper',
                        array('Label', array('class' => 'control-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                        ))
                     ->setValue('individual');;
        $activityType1->addMultiOptions(
                    array(
                        'block' => 'bloque de actividades',
                        'route' => 'ruta',
                        'individual' => 'individual'
                    ));
                    
        //Activity type2
        $activityType2 = new Zend_Form_Element_MultiCheckBox("activitytype2");
        $activityType2->setLabel("Registro")    
                     ->setOptions(array('required'=>false))
                     ->addDecorators(array(
                        'ViewHelper',
                        array('Label', array('class' => 'control-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                        ));
        $activityType2->addMultiOptions(
                    array(
                        'time'  => 'temporal',                        
                        'count' => 'contable'
                    ));
        
        //is Dependent
        $isDependent = new Zend_Form_Element_Hidden("isdependent");
        
        //isValid
        $isValid = new Zend_Form_Element_Hidden('isvalid');
        $isValid->setValue('true');
        
        //Block Activities
        $block = new Zend_Form_Element_MultiCheckBox("block");
        $block->setLabel("Actividades de Bloque")    
                     ->setOptions(array('required'=>true,'readonly'=>true))
                     ->addDecorators(array(
                        'ViewHelper',
                        array('Label', array('class' => 'control-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'controls','id'=>'block-activities-checkbox-options')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group','id'=>'block-activities-checkbox'))
                        ))
                      ->setValue("null")
                      ->addMultiOptions(array("null"=>"ninguna"))
                      ->setRegisterInArrayValidator(false);
                      
        //Route Places
        $route = new Zend_Form_Element_MultiCheckBox("route");
        $route->setLabel("Lugares en Ruta")    
                     ->setOptions(array('required'=>false,'readonly'=>true))
                     ->addDecorators(array(
                        'ViewHelper',
                        array('Label', array('class' => 'control-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'controls','id'=>'block-places-checkbox-options')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group','id'=>'block-places-checkbox'))
                        ))
                      ->setValue("null")
                      ->addMultiOptions(array("null"=>"Mi casa"))
                      ->setRegisterInArrayValidator(false);
        
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Agregar")
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
                                $name,
                                $description,
                                $activityType1,
                                $activityType2,
                                $block,
                                $route,
                                $isDependent,
                                $isValid,
                                $submit
                                ));         
    }


}

