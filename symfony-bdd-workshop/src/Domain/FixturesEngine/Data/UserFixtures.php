<?php

namespace Domain\FixturesEngine\Data;

use Domain\FixturesEngine\FixtureInterface;
use Domain\FixturesEngine\AbstractFixture;
use Domain\FixturesEngine\ReferenceRepository;
use Domain\Model\User\UserId;
use Domain\UseCase\CreateTemplate;
use Domain\UseCase\UpdateTemplateVersion;

class UserFixtures extends AbstractFixture implements FixtureInterface
{
    /** {@inheritdoc} */
    public function load(ReferenceRepository $referenceRepository)
    {
        $referenceRepository->addReference('user_1', new UserId('Zlatan'));
        $referenceRepository->addReference('user_2', new UserId('Mario'));
        $referenceRepository->addReference('user_3', new UserId('Didier'));
    }

    /** {@inheritdoc} */
    public function getOrder()
    {
        return 1;
    }
}
