<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\PlaceDependence
 *
 * @Table(name="placedependence")
 * @Entity(repositoryClass="Repository\PlaceDependence")
 */
class PlaceDependence
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
     * @var Dtad\Entity\Activity
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Activity")
     * @JoinColumns({
     *   @JoinColumn(name="activity_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $activity;
    
     /**
     * @var Dtad\Entity\Place
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Place")
     * @JoinColumns({
     *   @JoinColumn(name="place_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $place;
    
    /**
     * @var bigint $order
     *
     * @Column(name="eorder", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     * 
     */
    private $order;
    
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
     * Set order
     *
     * @param bigint $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return bigint 
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * Set place
     *
     * @param Dtad\Entity\Place $place
     */
    public function setPlace(\Dtad\Entity\Place $place)
    {
        $this->place = $place;
    }

    /**
     * Get place
     *
     * @return Dtad\Entity\Place 
     */
    public function getPlace()
    {
        return $this->place;
    }
}