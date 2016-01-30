<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 30/01/16
 * Time: 12:32
 */

namespace RegistrarBundle\Event;


use RegistrarBundle\Entity\RegistrationApplication;
use Symfony\Component\EventDispatcher\Event;

class RegistrarCreateEvent extends Event
{
    /**
     * @var RegistrationApplication
     */
    private $registrationApplication;

    /**
     * RegistrarCreateEvent constructor.
     * @param RegistrationApplication $registrationApplication
     */
    public function __construct(RegistrationApplication $registrationApplication)
    {
        $this->registrationApplication = $registrationApplication;
    }

    /**
     * @return RegistrationApplication
     */
    public function getRegistrationApplication()
    {
        return $this->registrationApplication;
    }
}