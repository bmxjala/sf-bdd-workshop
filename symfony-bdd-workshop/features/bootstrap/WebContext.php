<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use BehatBundle\Document\Banner;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;
use Doctrine\ODM\MongoDB\DocumentManager;
use Domain\UseCase\CreateTemplate;
use Domain\UseCase\CreateTheme;
use Domain\UseCase\UpdateTemplateDraft;
use Domain\UseCase\UpdateTemplateVersion;
use Domain\Model\Template;
use Domain\Model\Theme;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;

/**
 * Defines application features from the specific context.
 */
class WebContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use ParameterBagDictionary;
    use KernelDictionary;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        /** @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $purger = new MongoDBPurger($dm);
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->purge();
        $dm->clear();
    }

    /**
     * @Given banner :arg1 exists
     */
    public function bannerExists($bannerId)
    {
        $banner = new Banner();
        $banner->setId($bannerId);
        $this->getContainer()->get('behatbanner.repository.banner_repository')->save($banner);
    }

    /**
     * @Given banner :arg1 is disabled
     */
    public function bannerIsDisabled($bannerName)
    {
        $this->getContainer()->get('behatbanner.config_handler')->disable($bannerName);
    }

    /**
     * @When I wait for :arg1 seconds
     */
    public function iWaitForSeconds($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /**
     * @When I wait for ajax request to finish
     */
    public function iWaitForAjaxRequestToFinish()
    {
        $this->getSession()->wait(2000);
    }

    private function extractFromParameterBag($string)
    {
        return $this->getParameterBag()->replace($string);
    }
}
