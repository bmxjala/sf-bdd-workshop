<?php

namespace Domain\UseCase\UpdateTemplateVersion;

use Domain\Model\Template\TemplateId;
use Domain\Model\Template\TemplateVersionId;
use Domain\Model\Theme\ThemeId;
use Domain\Model\User\UserId;

class Command
{
    /**
     * @var TemplateId
     */
    public $templateId;

    /**
     * @var TemplateVersionId
     */
    public $templateVersionId;

    /**
     * @var UserId
     */
    public $userId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $plaintextContent;

    /**
     * @var string
     */
    public $htmlContent;

    /**
     * @var ThemeId
     */
    public $themeId;

    /**
     * @param TemplateId $templateId
     * @param TemplateVersionId $templateVersionId
     * @param $name
     * @param $plaintextContent
     * @param $htmlContent
     * @param $themeId
     */
    public function __construct(
        TemplateId $templateId,
        TemplateVersionId $templateVersionId,
        UserId $userId,
        $name,
        $plaintextContent,
        $htmlContent,
        ThemeId $themeId
    ) {
        $this->templateId = $templateId;
        $this->templateVersionId = $templateVersionId;
        $this->userId = $userId;
        $this->name = $name;
        $this->plaintextContent = $plaintextContent;
        $this->htmlContent = $htmlContent;
        $this->themeId = $themeId;
    }
}