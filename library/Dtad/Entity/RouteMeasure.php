<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\RouteMeasure
 *
 * @Table(name="route_measure")
 * @Entity(repositoryClass="Repository\RouteMeasure")
 */
class RouteMeasure
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
     * @var Dtad\Entity\Place
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Place")
     * @JoinColumns({
     *   @JoinColumn(name="start_place_id", referencedColumnName="id", nullable=true)
     * })
     */
     
    private $startplace;
    
    /**
     * @var Dtad\Entity\Place
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Place")
     * @JoinColumns({
     *   @JoinColumn(name="end_place_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $endplace;
    
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
     * Set startplace
     *
     * @param Dtad\Entity\Place $startplace
     */
    public function setStartPlace(\Dtad\Entity\Place $startplace)
    {
        $this->startplace = $startplace;
    }

    /**
     * Get startplace
     *
     * @return Dtad\Entity\Place 
     */
    public function getStartPlace()
    {
        return $this->startplace;
    }
    
    /**
     * Set endplace
     *
     * @param Dtad\Entity\Place $endplace
     */
    public function setEndPlace(\Dtad\Entity\Place $endplace)
    {
        $this->endplace = $endplace;
    }

    /**
     * Get endplace
     *
     * @return Dtad\Entity\Place 
     */
    public function getEndPlace()
    {
        return $this->endplace;
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