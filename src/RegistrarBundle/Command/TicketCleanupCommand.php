<?php namespace RegistrarBundle\Command;


use Doctrine\ORM\EntityManager;
use RegistrarBundle\Repository\RegistrationApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TicketCleanupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('registrar:ticket.cleanup')
            ->setDescription('Remove expired tickets')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var RegistrationApplicationRepository $repo */
        $repo = $em->getRepository('RegistrarBundle:RegistrationApplication');

        if ($expired = $repo->getExpired()) {
            foreach ($expired as $entity) {
                $em->remove($entity);
            }

            $em->flush();
        }
    }
}