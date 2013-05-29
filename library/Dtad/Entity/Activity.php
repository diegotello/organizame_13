<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Activity
 *
 * @Table(name="activity")
 * @Entity(repositoryClass="Repository\Activity")
 */
class Activity
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
     * @var Dtad\Entity\ActivityType
     *
     * @ManyToOne(targetEntity="Dtad\Entity\ActivityType")
     * @JoinColumns({
     *   @JoinColumn(name="activitytype_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $activitytype;

    /**
     * @var varchar(45) $name
     *
     * @Column(name="name", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", precision=0, scale=0, nullable=true, unique=false)
     */
    private $description;

    /**
     * @var int $isdependent
     *
     * @Column(name="isdependent", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isdependent;

    /**
     * @var int $estimate
     *
     * @Column(name="estimate", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $estimate;

    /**
     * Get id
     *
     * @return bigint 
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Get neme
     *
     * @return string 
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name){
        $this->name = $name;
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
     * Get activitytype
     *
     * @return Dtad\Entity\ActivityType
     */
    public function getActivityType(){
        return $this->activitytype;
    }

    /**
     * Set activitytype
     *
     * @param Dtad\Entity\ActivityType $activitytype
     */

    public function setActivityType($activitytype){
        $this->activitytype = $activitytype;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description){
        $this->description = $description;
    }

    /**
     * Get isdependent
     *
     * @return integer 
     */
    public function getIsDependent(){
        return $this->isdependent;
    }

    /**
     * Set isdependent
     *
     * @param integer $isdependent
     */
    public function setIsDependent($isdependent){
        $this->isdependent= $isdependent;
    }

    /**
     * Get estimate
     *
     * @return integer 
     */
    public function getEstimate(){
        return $this->estimate;
    }

    /**
     * Set estimate
     *
     * @param integer $estimate
     */
    public function setEstimate($estimate){
        $this->estimate= $estimate;
    }
}