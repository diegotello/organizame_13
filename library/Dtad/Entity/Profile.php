<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Profile
 *
 * @Table(name="profile")
 * @Entity(repositoryClass="Repository\Profile")
 */
class Profile
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
     * @var string $firstname
     *
     * @Column(name="firstname", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $firstname;

    /**
     * @var string $middlename
     *
     * @Column(name="middlename", type="string", length=45, precision=0, scale=0, nullable=true, unique=false)
     */
    private $middlename;

    /**
     * @var string $lastname
     *
     * @Column(name="lastname", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lastname;

	/**
     * @var Dtad\Entity\User
     *
     * @ManyToOne(targetEntity="Dtad\Entity\User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id", nullable=false, unique=true)
     * })
     */
    private $user;
    
    /**
     * @var Dtad\Entity\Place
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Place")
     * @JoinColumns({
     *   @JoinColumn(name="place_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $home;

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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * Set middlename
     *
     * @param string $middlename
     */
    public function setMiddleName($middlename)
    {
        $this->middlename = $middlename;
    }

    /**
     * Get middlename
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middlename;
    }

     /**
     * Set home
     *
     * @param Dtad\Entity\Place $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * Get home
     *
     * @return Dtad\Entity\Place 
     */
    public function getHome()
    {
        return $this->home;
    }

	/**
     * Set user
     *
     * @param Dtad\Entity\User $user
     */
    public function setUser(\Dtad\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Dtad\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}




