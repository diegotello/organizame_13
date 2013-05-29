<?php

class Dtad_Form_ActivityFromService extends Zend_Form
{

    public function init()
    {
    	//UserId
        $userId = new Zend_Form_Element_Text("user_id");
        $userId->addValidator(new Zend_Validate_Int())
              ->setOptions(array('required'=>true));
        //Name
        $name = new Zend_Form_Element_Text("name");
        $name->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
              ->setOptions(array('required'=>true));
        
        //Description
        $description = new Zend_Form_Element_Text("description");

        //Estimate
        $estimate = new Zend_Form_Element_Text("estimate");
        $estimate->addValidator(new Zend_Validate_Int());

        $this->addElements(array(
        						$userId,
                                $name,
                                $description,                                
                                $estimate
                                ));   

    }


}

