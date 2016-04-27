<?php

namespace AppBundle\Command;

use Domain\ReadModel\ProjectionPopulator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateProjectionCommand extends ProjectionCommand
{
    protected function configure()
    {

        $help = 'The <info>%command.name%</info> command populates (or re-populates) selected projection for Lendo Mail Service.';
        $help .= '<info>php %command.full_name% --name=populator-name</info>';

        $this
            ->setName('lendo:mailservice:projection:populate')
            ->setDescription('Populates (or re-populates) selected projection for Lendo Mail Service')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'Name of the projection you want to (re)populate',
                null
            )
            ->setHelp($help);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->validateParameters($input, $output);

        /** @var $populator ProjectionPopulator */
        $populator = $this->getPopulator();
        $populator->run();

        $output->writeln('Finished!');
        $output->writeln(sprintf(
            'Removed <info>%d</info> projections',
            $populator->getStats()->getRemoved()
        ));
        $output->writeln(sprintf(
            'Processed <info>%d</info> of total <info>%d</info> events from EventStorage',
            $populator->getStats()->getProcessedEvents(),
            $populator->getStats()->getTotalEvents()
        ));
        $output->writeln(sprintf(
            'Loaded <info>%d</info> new projections',
            $populator->getStats()->getPopulated()
        ));

        return ProjectionCommand::EXIT_CODE_SUCCESS;
    }
}
