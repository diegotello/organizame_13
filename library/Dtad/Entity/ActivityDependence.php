<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\ActivityDependence
 *
 * @Table(name="activitydependence")
 * @Entity(repositoryClass="Repository\ActivityDependence")
 */
class ActivityDependence
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
     *   @JoinColumn(name="dependent_activity_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $dependentactivity;
    
     /**
     * @var Dtad\Entity\Activity
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Activity")
     * @JoinColumns({
     *   @JoinColumn(name="independent_activity_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $independentactivity;
    
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
     * Set dependentactivity
     *
     * @param Dtad\Entity\Activity $dependentactivity
     */
    public function setDependentActivity(\Dtad\Entity\Activity $dependentactivity)
    {
        $this->dependentactivity = $dependentactivity;
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
     * Get dependentactivity
     *
     * @return Dtad\Entity\Activity 
     */
    public function getDependentActivity()
    {
        return $this->dependentactivity;
    }
    
    /**
     * Set independentactivity
     *
     * @param Dtad\Entity\Activity $independentactivity
     */
    public function setIndependentActivity(\Dtad\Entity\Activity $independentactivity)
    {
        $this->independentactivity = $independentactivity;
    }

    /**
     * Get independentactivity
     *
     * @return Dtad\Entity\Activity 
     */
    public function getIndependentActivity()
    {
        return $this->independentactivity;
    }
}