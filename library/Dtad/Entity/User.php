<?php

namespace Dtad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dtad\Entity\User
 *
 * @Table(name="user")
 * @Entity(repositoryClass="Repository\User")
 */
class User
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
     * @var string $username
     *
     * @Column(name="username", type="string", length=45, precision=0, scale=0, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string $email
     *
     * @Column(name="email", type="string", length=45, precision=0, scale=0, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var string $salt
     *
     * @Column(name="salt", type="string", length=128, precision=0, scale=0, nullable=false, unique=false)
     */
    private $salt;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=128, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var datetime $createdat
     *
     * @Column(name="created_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdat;

    /**
     * @var Dtad\Entity\Role
     *
     * @ManyToOne(targetEntity="Dtad\Entity\Role")
     * @JoinColumns({
     *   @JoinColumn(name="role_id", referencedColumnName="id", nullable=false, unique=false)
     * })
     */
    private $role;


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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createdat
     *
     * @param datetime $createdat
     */
    public function setCreatedAt($createdat)
    {
        $this->createdat = $createdat;
    }

    /**
     * Get createdat
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdat;
    }

    /**
     * Set role
     *
     * @param Dtad\Entity\Role $role
     */
    public function setRole(\Dtad\Entity\Role $role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return Dtad\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }
}