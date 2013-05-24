<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Place
 *
 * @Table(name="place")
 * @Entity(repositoryClass="Repository\Place")
 */
class Place
{
	/**
     * @var bigint $id
     *
     * @Column(name="id", type="bigint", precision=0, scale=0, nullable=false, unique=true)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var Dtad\Entity\User
     *
     * @ManyToOne(targetEntity="Dtad\Entity\User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $user;

     /**
     * @var string $description
     *
     * @Column(name="description", type="string", length=45, precision=0, scale=0, nullable=true, unique=false)
     */
    private $description;

     /**
     * @var string $address
     *
     * @Column(name="address", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $address;

     /**
     * @var string $secondaddress
     *
     * @Column(name="second_address", type="string", length=45, precision=0, scale=0, nullable=true, unique=false)
     */
    private $secondaddress;

     /**
     * @var string $lat
     *
     * @Column(name="lat", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lat;
    
    /**
     * @var string $lng
     *
     * @Column(name="lng", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lng;

     /**
     * Get id
     *
     * @return bigint 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set secondaddress
     *
     * @param string $secondaddress
     */
    public function setSecondAddress($secondaddress)
    {
        $this->secondaddress = $secondaddress;
    }

    /**
     * Get secondaddress
     *
     * @return string 
     */
    public function getSecondAddress()
    {
        return $this->secondaddress;
    }

    /**
     * Set lat
     *
     * @param string $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }
    
     /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }
    
    /**
     * Get user
     *
     * @return Dtad\Entity\User
     */
    public function getUser(){
        return $this->user;
    }

    /**
     * Set user
     *
     * @param Dtad\Entity\User $user
     */

    public function setUser($user){
        $this->user = $user;
    }
    
    /**
     * Set lng
     *
     * @param string $lng
     */
    public function setLong($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLong()
    {
        return $this->lng;
    }   
} 

