<?php

namespace Dtad\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class DtadEntityMeasureProxy extends \Dtad\Entity\Measure implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function setActivityId(\Dtad\Entity\Activity $activity)
    {
        $this->__load();
        return parent::setActivityId($activity);
    }

    public function getActivity()
    {
        $this->__load();
        return parent::getActivity();
    }

    public function setStart($start)
    {
        $this->__load();
        return parent::setStart($start);
    }

    public function getStart()
    {
        $this->__load();
        return parent::getStart();
    }

    public function setEnd($end)
    {
        $this->__load();
        return parent::setEnd($end);
    }

    public function getEnd()
    {
        $this->__load();
        return parent::getEnd();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'activity', 'start', 'end');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}