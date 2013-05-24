<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Measure
 *
 * @Table(name="measure")
 * @Entity(repositoryClass="Repository\Measure")
 */
class Measure
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
     *   @JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $activity;
    
    /**
     * @var Dtad\Entity\Cronogram
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Cronogram")
     * @JoinColumns({
     *   @JoinColumn(name="cronogram_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $cronogram;
    
    /**
     * @var bigint $start
     *
     * @Column(name="start", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     * 
     */
    private $start;
    
    /**
     * @var bigint $end
     *
     * @Column(name="end", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     * 
     */
    private $end;

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
     * Set cronogram
     *
     * @param Dtad\Entity\Cronogram $cronogram
     */
    public function setCronogram(\Dtad\Entity\Cronogram $cronogram)
    {
        $this->cronogram = $cronogram;
    }

    /**
     * Get cronogram
     *
     * @return Dtad\Entity\Cronogram
     */
    public function getCronogram()
    {
        return $this->cronogram;
    }
    
    /**
     * Set start
     *
     * @param bigint $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return bigint 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param bigint $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return bigint 
     */
    public function getEnd()
    {
        return $this->end;
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