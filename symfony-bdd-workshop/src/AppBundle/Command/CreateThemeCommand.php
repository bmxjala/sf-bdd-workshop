<?php

namespace AppBundle\Command;

use Domain\Model\Theme;
use Domain\ReadModel\Projection\ThemeListProjection;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\CreateTheme;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateThemeCommand extends ContainerAwareCommand implements CreateTheme\Responder
{
    const EXIT_CODE_SUCCESS = 0;
    const EXIT_CODE_EMPTY_PARAMETER = 5001;
    const EXIT_CODE_ALREADY_EXISTS = 5002;
    const EXIT_CODE_UNKNOWN_ERROR = 5003;
    const EXIT_CODE_FILE_NOT_EXISTS = 5004;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $isDefault = false;

    /**
     * @var string
     */
    private $file;

    /**
     * @var Theme
     */
    private $addedTheme;

    protected function configure()
    {
        $help = 'The <info>%command.name%</info> command creates new theme for Lendo Mail Service.';
        $help .= '<info>php %command.full_name% --name=theme-name [--content=...] [--default=false]</info>';

        $this
            ->setName('lendo:mailservice:theme:create')
            ->setDescription('Creates a theme for Lendo Mail Service')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.',
                null
            )
            ->addOption(
                'content',
                null,
                InputOption::VALUE_OPTIONAL,
                'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..',
                '<div>{{main}}</div>'
            )
            ->addOption(
                'file',
                null,
                InputOption::VALUE_OPTIONAL,
                'HTML content from file',
                null
            )
            ->addOption(
                'default',
                null,
                InputOption::VALUE_OPTIONAL,
                '',
                false
            )
            ->setHelp($help);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getCommandOptions($input);

        $this->validateCommandOptions();

        $createThemeUseCase = new CreateTheme(
            $this->getContainer()->get('event_bus'),
            $this->getContainer()->get('event_storage')
        );
        $createThemeUseCase->execute(new CreateTheme\Command($this->name, $this->content, $this->isDefault), $this);

        if (empty($this->addedTheme)) {
            $errorMessage = sprintf('Unknown problems occurred when tried to add new theme');
            throw new \Exception($errorMessage, self::EXIT_CODE_UNKNOWN_ERROR);
        }

        $output->writeln(
            sprintf(
                'Added new '.($this->addedTheme->getIsDefault(
                ) === true ? 'DEFAULT ' : '').'theme with name <info>%s</info>',
                $this->addedTheme->getName()
            )
        );

        return self::EXIT_CODE_SUCCESS;
    }

    /**
     * @param Theme $theme
     */
    public function themeSuccessfullyCreated(Theme $theme)
    {
        $this->addedTheme = $theme;
    }

    /**
     * @param $name
     * @return bool
     */
    private function themeExists($name)
    {
        /** @var ProjectionStorage $projectionStorage */
        $projectionStorage = $this->getContainer()->get('projection_storage');
        $existingThemes = $projectionStorage->find('theme-list');
        foreach ($existingThemes as $theme) {
            /** @var ThemeListProjection $theme */
            if ($theme->getName() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param InputInterface $input
     */
    private function getCommandOptions(InputInterface $input)
    {
        $this->name = $input->getOption('name');
        $this->content = $input->getOption('content');
        $this->isDefault = $input->getOption('default') == 'true' ? true : false;
        $this->file = $input->getOption('file');
    }

    /**
     * @throws \Exception
     */
    private function validateCommandOptions()
    {
        if (empty($this->name)) {
            $errorMessage = sprintf('Empty required parameter <info>name</info>');

            throw new \Exception($errorMessage, self::EXIT_CODE_EMPTY_PARAMETER);
        }

        if ($this->themeExists($this->name)) {
            $errorMessage = sprintf('Theme with name <info>%s</info> already exists', $this->name);

            throw new \Exception($errorMessage, self::EXIT_CODE_ALREADY_EXISTS);
        }

        if (!empty($this->file)) {
            if (file_exists($this->file)) {
                $this->content = file_get_contents($this->file);
            } else {
                $errorMessage = sprintf('Theme file <info>%s</info> does not exists', $this->file);
                throw new \Exception($errorMessage, self::EXIT_CODE_FILE_NOT_EXISTS);
            }
        }
    }
}
