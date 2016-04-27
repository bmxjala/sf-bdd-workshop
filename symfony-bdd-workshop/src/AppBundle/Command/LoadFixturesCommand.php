<?php

namespace AppBundle\Command;

use Domain\FixturesEngine\EventStorageNotEmptyException;
use Domain\FixturesEngine\FixturesExecutor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $help = 'The <info>%command.name%</info> command loads defined fixtures for Lendo Mail Service. Usage: '."\n";
        $help .= '<info>php %command.full_name% [--force=true]</info>';

        $this
            ->setName('lendo:mailservice:fixtures:load')
            ->setDescription('Loads defined fixtures for Lendo Mail Service')
            ->setHelp($help)
            ->addOption(
                'force',
                null,
                InputOption::VALUE_OPTIONAL,
                'Forces fixtures to be loaded when EventStorage is not empty.',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixturesExecutor = new FixturesExecutor(
            $this->getContainer()->get('event_bus'),
            $this->getContainer()->get('event_storage'),
            $this->getContainer()->get('projection_storage')
        );

        if($input->getOption('force') == 'true') {
            $fixturesExecutor->force();
        }

        try {
            $fixturesExecutor->run();
        } catch (EventStorageNotEmptyException $e) {
            $output->writeln(sprintf('EventStorage is not empty. Please clear EventStorage or use command with <info>--force=true</info> option.'));
            return;
        }

        $output->writeln(
            sprintf(
                'Fixtures have been loaded.'
            )
        );
    }
}
