<?php

namespace Dtad\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class DtadEntityPlaceProxy extends \Dtad\Entity\Place implements \Doctrine\ORM\Proxy\Proxy
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

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setAddress($address)
    {
        $this->__load();
        return parent::setAddress($address);
    }

    public function getAddress()
    {
        $this->__load();
        return parent::getAddress();
    }

    public function setSecondAddress($secondaddress)
    {
        $this->__load();
        return parent::setSecondAddress($secondaddress);
    }

    public function getSecondAddress()
    {
        $this->__load();
        return parent::getSecondAddress();
    }

    public function setLat($lat)
    {
        $this->__load();
        return parent::setLat($lat);
    }

    public function getLat()
    {
        $this->__load();
        return parent::getLat();
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }

    public function setUser($user)
    {
        $this->__load();
        return parent::setUser($user);
    }

    public function setLong($lng)
    {
        $this->__load();
        return parent::setLong($lng);
    }

    public function getLong()
    {
        $this->__load();
        return parent::getLong();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'user', 'description', 'address', 'secondaddress', 'lat', 'lng');
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