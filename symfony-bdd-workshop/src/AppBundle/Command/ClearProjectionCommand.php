<?php

namespace AppBundle\Command;

use Domain\ReadModel\ProjectionPopulator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearProjectionCommand extends ProjectionCommand
{

    protected function configure()
    {
        $help = 'The <info>%command.name%</info> command removes all items from selected projection for Lendo Mail Service.';
        $help .= '<info>php %command.full_name% --name=populator-name</info>';

        $this
            ->setName('lendo:mailservice:projection:clear')
            ->setDescription('Clears selected projection for Lendo Mail Service')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'Name of the projection you want to clear',
                null
            )
            ->setHelp($help);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validateParameters($input, $output);

        /** @var $populator ProjectionPopulator */
        $populator = $this->getPopulator();
        $populator->clear();

        $output->writeln('Finished!');
        $output->writeln(sprintf(
            'Removed  <info>%s</info> projections',
            $populator->getStats()->getRemoved()
        ));

        return ProjectionCommand::EXIT_CODE_SUCCESS;
    }
}
