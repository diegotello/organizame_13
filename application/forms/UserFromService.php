<?php

class Dtad_Form_UserFromService extends Zend_Form
{

    public function init()
    {
        //User Name
        $firstname = new Zend_Form_Element_Text("firstname");
        $firstname->setLabel("Primer Nombre")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true));
            
        
        $middlename = new Zend_Form_Element_Text("middlename");
        $middlename->setLabel("Segundo Nombre")
                   ->addValidator(new Zend_Validate_StringLength(array('max'=>45)));

        $lastname = new Zend_Form_Element_Text("lastname");
        $lastname->setLabel("Apellido")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                 ->setOptions(array('required'=>true));

        $username = new Zend_Form_Element_Text("username");
        $username->setLabel("Nombre de Usuario")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                 ->addValidator(new Zend_Validate_Alnum())
                 ->addValidator(new Dtad_Validate_Db_NoRecordExists(array('table'=>'User','field'=>'username')))
                 ->setOptions(array('required'=>true));
                
        $email = new Zend_Form_Element_Text("email");
        $email->setLabel("Email")
              ->addValidator(new Zend_Validate_EmailAddress())
              ->addValidator(new Dtad_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email')));

        $password = new Zend_Form_Element_Password("password");
        $password->setLabel("Contraseña")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>128)))
                 ->setOptions(array('required'=>true));

         $this->addElements(array(
                    $username,
                    $firstname,
                    $middlename,
                    $lastname,
                    $email,
                    $password
                 )); 
    }


}

