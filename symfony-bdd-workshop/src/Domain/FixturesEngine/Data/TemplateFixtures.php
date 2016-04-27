<?php

namespace Domain\FixturesEngine\Data;

use Domain\FixturesEngine\FixtureInterface;
use Domain\FixturesEngine\AbstractFixture;
use Domain\FixturesEngine\ReferenceRepository;
use Domain\Model\Template;
use Domain\Model\TemplateDraft;
use Domain\Model\TemplateVersion;
use Domain\Model\Theme;
use Domain\Model\User\UserId;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\CreateTemplate;
use Domain\UseCase\CreateTheme;
use Domain\UseCase\UpdateTemplateVersion;

class TemplateFixtures extends AbstractFixture implements FixtureInterface, CreateTemplate\Responder, UpdateTemplateVersion\Responder, CreateTheme\Responder
{
    /**
     * @var Template
     */
    private $template;

    /**
     * @var TemplateVersion
     */
    private $templateVersion;

    /**
     * @var TemplateDraft
     */
    private $templateDraft;

    /**
     * @var Theme
     */
    private $theme;

    /** {@inheritdoc} */
    public function load(ReferenceRepository $referenceRepository)
    {
        $this->loadTemplate1($referenceRepository);
        $this->loadTemplate2($referenceRepository);
        $this->loadTemplate3($referenceRepository);
    }

    public function getOrder()
    {
        return 2;
    }

    /** {@inheritdoc} */
    public function templateSuccessfullyCreated(Template $template, TemplateVersion $templateVersion, TemplateDraft $templateDraft)
    {
        $this->template = $template;
        $this->templateVersion = $templateVersion;
        $this->templateDraft = $templateDraft;
    }

    /** {@inheritdoc} */
    public function templateSuccessfullyUpdated(Template $template, TemplateVersion $templateVersion)
    {
        $this->template = $template;
        $this->templateVersion = $templateVersion;
    }

    /** {@inheritdoc} */
    public function themeSuccessfullyCreated(Theme $theme)
    {
        $this->theme = $theme;
    }

    /** {@inheritdoc} */
    public function templateCreatingFailed(\Exception $e)
    {
        throw new \Exception($e);
    }

    /** {@inheritdoc} */
    public function templateUpdateFailed(\Exception $e)
    {
        throw new \Exception($e);
    }

    private function loadTemplate1(ReferenceRepository $referenceRepository)
    {
        $user = $referenceRepository->getReference('user_1');

        $createThemeUseCase = new CreateTheme($this->eventBus, $this->eventStorage);
        $createThemeUseCase->execute(new CreateTheme\Command('Default', '<div>{{main}}</div>', true), $this);

        $createTemplateUseCase = new CreateTemplate($this->eventBus, $this->eventStorage);
        $createTemplateUseCase->execute(new CreateTemplate\Command($user), $this);

        $command = new UpdateTemplateVersion\Command(
            $this->template->getAggregateId(),
            $this->templateVersion->getAggregateId(),
            $user,
            $name = 'Basic template',
            $plaintextContent = 'Hello {{name}}',
            $htmlContent = '<h3>Hello {{name}}</h3>',
            $this->theme->getAggregateId()
        );

        $updateTemplateUseCase = new UpdateTemplateVersion($this->eventBus, $this->eventStorage, $this->projectionStorage);
        $updateTemplateUseCase->execute($command, $this);

        $referenceRepository->addReference('template_1', $this->template);
        $referenceRepository->addReference('templateVersion_1', $this->templateVersion);
        $referenceRepository->addReference('templateDraft_1', $this->templateDraft);
    }

    private function loadTemplate2(ReferenceRepository $referenceRepository)
    {
        $user = $referenceRepository->getReference('user_2');

        $createThemeUseCase = new CreateTheme($this->eventBus, $this->eventStorage);
        $createThemeUseCase->execute(new CreateTheme\Command('Default', '<div>{{main}}</div>', true), $this);

        $createTemplateUseCase = new CreateTemplate($this->eventBus, $this->eventStorage);
        $createTemplateUseCase->execute(new CreateTemplate\Command($user), $this);

        $command = new UpdateTemplateVersion\Command(
            $this->template->getAggregateId(),
            $this->templateVersion->getAggregateId(),
            $user,
            $name = 'Static template (no variables)',
            $plaintextContent = 'This is static template without any variables',
            $htmlContent = '<div>This is static template without any variables</div>',
            $this->theme->getAggregateId()
        );

        $updateTemplateUseCase = new UpdateTemplateVersion($this->eventBus, $this->eventStorage, $this->projectionStorage);
        $updateTemplateUseCase->execute($command, $this);

        $referenceRepository->addReference('template_2', $this->template);
        $referenceRepository->addReference('templateVersion_2', $this->templateVersion);
        $referenceRepository->addReference('templateDraft_2', $this->templateDraft);
    }

    private function loadTemplate3(ReferenceRepository $referenceRepository)
    {
        $user = $referenceRepository->getReference('user_3');

        $createThemeUseCase = new CreateTheme($this->eventBus, $this->eventStorage);
        $createThemeUseCase->execute(new CreateTheme\Command('Default', '<div>{{main}}</div>', true), $this);

        $createTemplateUseCase = new CreateTemplate($this->eventBus, $this->eventStorage);
        $createTemplateUseCase->execute(new CreateTemplate\Command($user), $this);

        $command = new UpdateTemplateVersion\Command(
            $this->template->getAggregateId(),
            $this->templateVersion->getAggregateId(),
            $user,
            $name = 'Standard welcome',
            $plaintextContent = 'Hello {{username}},'."\n\n".'Today is {{weekday}}. The weather in {{city}} is {{weather}}.'."\n\n".'Have a nice day!'."\n".'Bye {{username}}',
            $htmlContent = '<p>Hello {{username}},</p>'."\n\n".'<p>Today is <strong>{{weekday}}</strong>. The weather in {{city}} is <strong>{{weather}}</strong>.</p>'."\n\n".'<p>Have a nice day!<br>'."\n".'Bye {{username}}</p>',
            $this->theme->getAggregateId()
        );

        $updateTemplateUseCase = new UpdateTemplateVersion($this->eventBus, $this->eventStorage, $this->projectionStorage);
        $updateTemplateUseCase->execute($command, $this);

        $referenceRepository->addReference('template_3', $this->template);
        $referenceRepository->addReference('templateVersion_3', $this->templateVersion);
        $referenceRepository->addReference('templateDraft_3', $this->templateDraft);
    }
}
