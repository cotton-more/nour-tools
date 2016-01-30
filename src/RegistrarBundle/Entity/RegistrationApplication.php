<?php

namespace RegistrarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * RegistrationApplication
 *
 * @ORM\Table(name="registration_application")
 * @ORM\Entity(repositoryClass="RegistrarBundle\Repository\RegistrationApplicationRepository")
 */
class RegistrationApplication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="uuid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_at", type="datetime", nullable=true)
     */
    private $expireAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket", type="string", length=255)
     */
    private $ticket;

    public function __construct()
    {
        $this->id = Uuid::uuid4();

        $this->generateTicket();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return RegistrationApplication
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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
     * Set expireAt
     *
     * @param \DateTime $expireAt
     *
     * @return RegistrationApplication
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * Get expireAt
     *
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * Get tiket
     *
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    public function generateTicket()
    {
        $this->ticket = md5(random_bytes(10));
    }
}

