<?php

class Dtad_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setName("login");
        $this->setMethod('post');
        $this->setAttrib('class','well span4');

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 45)),
            ),
            'required'   => true,
            'label'      => 'Nombre de usuario',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Password',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Iniciar Sesion',
            'class'    => 'btn btn-large btn-primary'
        ));

        $url = new Zend_Form_Element_Hidden("url");
        $this->addElement($url);
    }
}

