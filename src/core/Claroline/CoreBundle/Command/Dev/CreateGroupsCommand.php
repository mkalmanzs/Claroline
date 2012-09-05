<?php

namespace Claroline\CoreBundle\Command\Dev;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Claroline\CoreBundle\Library\Security\PlatformRoles;
use Claroline\CoreBundle\Library\Workspace\Configuration;
use Claroline\CoreBundle\Entity\Group;
/**
 * Creates an user, optionaly with a specific role (default to simple user).
 */
class CreateGroupsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('claroline:groups:create')
            ->setDescription('Creates some groups with the current registerd users and roles');
        $this->setDefinition(array(
            new InputArgument('amount', InputArgument::REQUIRED, 'The number of groups'),
        ));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $params = array(
            'amount' => 'amount',

        );

        foreach ($params as $argument => $argumentName) {
            if (!$input->getArgument($argument)) {
                $input->setArgument(
                    $argument, $this->askArgument($output, $argumentName)
                );
            }
        }
    }

    protected function askArgument(OutputInterface $output, $argumentName)
    {
        $argument = $this->getHelper('dialog')->askAndValidate(
            $output, "Enter the {$argumentName}: ", function($argument) {
                if (empty($argument)) {
                    throw new \Exception('This argument is required');
                }

                return $argument;
            }
        );

        return $argument;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('amount');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('ClarolineCoreBundle:User')->findAll();
        $roles = $em->getRepository('ClarolineCoreBundle:Role');
        $maxUsersOffset = count($users);
        $maxUsersOffset--;
        $maxRolesOffset = count($roles);
        $maxRolesOffset--;

        for ($i=0; $i < $number; $i++) {

            $group = new Group();
            $userNumber = rand(0, $maxUsersOffset);
            $group->setName($this->getContainer()->get('claroline.resource.utilities')->generateGuid());
            $userAddedIds = array();

            for ($j=0; $j <= $userNumber; $j++) {
                $created = false;
                while(false == $created) {
                    $id = rand(0, $maxUsersOffset);
                    if (!array_key_exists($id, $userAddedIds)) {
                        $userAddedIds[] = $id;
                        $group->addUser($users[$id]);
                        $created = true;
                    }
                }
            }

            $em->persist($group);
            $em->flush();
        }
    }
}