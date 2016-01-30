<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 28/01/16
 * Time: 17:16
 */

namespace RegistrarBundle\EventListener;


use RegistrarBundle\Event\RegistrarCreateEvent;
use RegistrarBundle\RegistrarEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrarListener implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * RegistrarListener constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function onRegistrarCreate(RegistrarCreateEvent $event)
    {
        $registrationApplication = $event->getRegistrationApplication();

        $email = $registrationApplication->getEmail();

        $body = $this->twig->render('RegistrarBundle:emails:confirm_email.html.twig', array(
            'email'  => $email,
            'ticket' => $registrationApplication->getTicket(),
        ));

        $message = \Swift_Message::newInstance()
            ->setSubject('Email confirmation required')
            ->setFrom('registrar+no-reply@nourriture.ru')
            ->setTo($email)
            ->setBody($body)
        ;
        $this->mailer->send($message);
    }
    
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            RegistrarEvents::CREATE => 'onRegistrarCreate',
        );
    }
}