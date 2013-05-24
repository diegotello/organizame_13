<?php

class Dtad_Validate_Db_NoRecordExists extends Zend_Validate_Abstract{
    
    const ERROR_RECORD_FOUND    = 'recordFound';
    protected $_messageTemplates = array(
        self::ERROR_RECORD_FOUND    => "A record matching '%value%' was found"
    );
    
    private $_table;
    private $_field;
    
    public function setTable($table){
        $this->_table = $table;
    }
    
    public function getTable(){
        return $this->_table;
    }
    
    public function setField($field){
        $this->_field = $field;
    }
    
    public function getField(){
        return $this->_field;
    }
    
    public function __construct($options) {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (func_num_args() > 1) {
            $options       = func_get_args();
            $temp['table'] = array_shift($options);
            $temp['field'] = array_shift($options);

            $options = $temp;
        }

        if (!array_key_exists('table', $options) ) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception('Table option missing!');
        }

        if (!array_key_exists('field', $options)) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception('Field option missing!');
        }

        $this->setField($options['field']);
        if (array_key_exists('table', $options)) {
            $this->setTable($options['table']);
        }
    } 
    public function isValid($value){
        $valid = true;
        $this->_setValue($value);
        $em = Zend_Registry::get("doctrine")->getEntityManager();
        
        $select = "select t from Dtad\Entity\\" . $this->getTable()." t where t.".$this->getField()."='$value'";
        $query = $em->createQuery($select);
        
        $result = $query->getResult();
        if ($result) {
            $valid = false;
            $this->_error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}