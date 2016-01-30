<?php namespace RegistrarBundle;


use BitrixBundle\Repository\BUserRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use RegistrarBundle\Entity\RegistrationApplication;
use RegistrarBundle\Event\RegistrarCreateEvent;
use RegistrarBundle\Repository\RegistrationApplicationRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegistrationExecutor
{
    private $ticketExpireHours = 24;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * RegistrationExecutor constructor.
     * @param Registry $doctrine
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(Registry $doctrine, EventDispatcherInterface $dispatcher)
    {
        $this->doctrine = $doctrine;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $email
     * @throws \Exception
     */
    public function handleEmail($email)
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (false === $email) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        /** @var BUserRepository $buserRepo */
        $buserRepo = $this->doctrine->getRepository('BitrixBundle:BUser', 'bitrix');
        if ($buserRepo->emailExists($email)) {
            throw new \Exception('Email already exists');
        }

        /** @var RegistrationApplicationRepository $registrationApplicationRepo */
        $registrationApplicationRepo = $this->doctrine->getRepository('RegistrarBundle:RegistrationApplication');

        /** @var RegistrationApplication $registrationApplication */
        if (!($registrationApplication = $registrationApplicationRepo->getUnexpired($email))) {
            $registrationApplication = new RegistrationApplication();
            $registrationApplication->setEmail($email);
        }

        $expireAt = new \DateTime();
        $expireAt->add(new \DateInterval('PT'.$this->ticketExpireHours.'H'));
        $registrationApplication->setExpireAt($expireAt);

        $em = $this->doctrine->getManager();
        $em->persist($registrationApplication);
        $em->flush();

        $event = new RegistrarCreateEvent($registrationApplication);
        $this->dispatcher->dispatch(RegistrarEvents::CREATE, $event);
    }

    private function expireRegistrationApplication(RegistrationApplication $registrationApplication)
    {
        $registrationApplication->setExpireAt(null);

        $em = $this->doctrine->getManager();
        $em->persist($registrationApplication);
        $em->flush();
    }

    /**
     * @param string $ticket
     * @param string $email
     * @return bool
     */
    public function validate($ticket, $email = null)
    {
        if (!$ticket) {
            throw new \InvalidArgumentException('Invalid ticket');
        }

        /** @var RegistrationApplicationRepository $registrationApplicationRepo */
        $registrationApplicationRepo = $this->doctrine->getRepository('RegistrarBundle:RegistrationApplication');

        if ($registrationApplication = $registrationApplicationRepo->getUnexpired($email, $ticket)) {
            $this->expireRegistrationApplication($registrationApplication);

            return true;
        } else {
            throw new \RuntimeException('Invalid ticket');
        }

        return false;
    }
}