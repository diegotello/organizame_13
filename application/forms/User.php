<?php

class Dtad_Form_User extends Zend_Form
{

    public function init()
    {
        //settings                
        $this->setAction('/user/create');
        $this->setMethod('post');
        $this->setName('newuserform');
        $this->setAttrib('class','well span6 form-horizontal');

        $em = Zend_Registry::get("doctrine")->getEntityManager();
        
        //User Name
        $firstname = new Zend_Form_Element_Text("firstname");
        $firstname->setLabel("Primer Nombre")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
            
        
        $middlename = new Zend_Form_Element_Text("middlename");
        $middlename->setLabel("Segundo Nombre")
                   ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                   ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));

        $lastname = new Zend_Form_Element_Text("lastname");
        $lastname->setLabel("Apellido")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                 ->setOptions(array('required'=>true))
                 ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));

        $lat = new Zend_Form_Element_Text("lat");
        $lat->setLabel("Latitud")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true,'readonly'=>true))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
                    
        $long = new Zend_Form_Element_Text("long");
        $long->setLabel("Longitud")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true,'readonly'=>true))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    )); 
                    
        $homeaddress = new Zend_Form_Element_Text("homeaddress");
        $homeaddress->setLabel("Direccion")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->setOptions(array('required'=>true))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    )); 

        $username = new Zend_Form_Element_Text("username");
        $username->setLabel("Nombre de Usuario")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                 ->addValidator(new Zend_Validate_Alnum())
                 ->addValidator(new Dtad_Validate_Db_NoRecordExists(array('table'=>'User','field'=>'username')))
                 ->setOptions(array('required'=>true))
                 ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
                
        $email = new Zend_Form_Element_Text("email");
        $email->setLabel("Email")
              ->addValidator(new Zend_Validate_EmailAddress())
              ->addValidator(new Dtad_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email')))
              ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));

        $password = new Zend_Form_Element_Password("password");
        $password->setLabel("Contraseña")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>128)))
                 ->setOptions(array('required'=>true))
                 ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    )); 
         
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Crear Usuario")
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
                    $username,
                    $firstname,
                    $middlename,
                    $lastname,
                    $lat,
                    $long,
                    $homeaddress,
                    $email,
                    $password,
                    $submit
                 ));
    }


}

