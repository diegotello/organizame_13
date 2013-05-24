 <?php

class Dtad_Form_Place extends Zend_Form
{

    public function init()
    {
        $this->setAction('/place');
        $this->setMethod('post');
        $this->setName('newplaceform');
        $this->setAttrib('class','well span6 form-horizontal');
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        
        $description = new Zend_Form_Element_Text("description");
        $description->setLabel("Descripcion")
                  ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                  ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
        
        $lat = new Zend_Form_Element_Text("lat");
        $lat->setLabel("Latitud")
                   ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                   ->setOptions(array('readonly'=>true))
                   ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
        
        $long = new Zend_Form_Element_Text("long");
        $long->setLabel("Longitud")
                   ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                   ->setOptions(array('readonly'=>true))
                   ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));
        
        $address = new Zend_Form_Element_Text("firstaddress");
        $address->setLabel("Direccion")
                   ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                   ->setOptions(array('required'=>true))
                   ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));

        $secondaddress = new Zend_Form_Element_Text("secondaddress");
        $secondaddress->setLabel("Segunda Direccion")
                 ->addValidator(new Zend_Validate_StringLength(array('max'=>45)))
                 ->setOptions(array('required'=>false))
                 ->addDecorators(array(
                    'ViewHelper',
                    array('Label', array('class' => 'control-label')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
                    ));

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
                                $description,
                                $lat,
                                $long,
                                $address,
                                $secondaddress,
                                $submit
                                ));         
      }
}
    