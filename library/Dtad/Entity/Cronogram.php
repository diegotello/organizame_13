<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Cronogram
 *
 * @Table(name="cronogram")
 * @Entity(repositoryClass="Repository\Cronogram")
 */
class Cronogram
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
     * @var Dtad\Entity\Activity
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Activity")
     * @JoinColumns({
     *   @JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $activity;
    
    /**
     * @var string $day
     *
     * @Column(name="day", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $day;
    
    /**
     * @var string $start
     *
     * @Column(name="start", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     * 
     */
    private $start;
    
    /**
     * @var string $status
     *
     * @Column(name="status", type="string", length=45, precision=0, scale=0, nullable=false, unique=false)
     */
    private $status;

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
     * Set activity
     *
     * @param Dtad\Entity\Activity $activity
     */
    public function setActivity(\Dtad\Entity\Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get activity
     *
     * @return Dtad\Entity\Activity 
     */
    public function getActivity()
    {
        return $this->activity;
    }
    
    /**
     * Set day
     *
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * Get day
     *
     * @return string 
     */
    public function getDay()
    {
        return $this->day;
    }
    
    /**
     * Set start
     *
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return string 
     */
    public function getStart()
    {
        return $this->start;
    }
    
    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
} 

