<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\Role
 *
 * @Table(name="role")
 * @Entity(repositoryClass="Repository\Role")
 */
class Role
{
    /**
     * @var bigint $id
     *
     * @Column(name="id", type="bigint", precision=0, scale=0, nullable=false, unique=true)
     * @Id
     */
    private $id;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=45, precision=0, scale=0, nullable=false, unique=true)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
