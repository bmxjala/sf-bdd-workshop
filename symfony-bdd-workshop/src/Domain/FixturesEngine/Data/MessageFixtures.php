<?php

namespace Domain\FixturesEngine\Data;

use Domain\Model\Mailing\EmailAddress;
use Domain\Model\Mailing\Message;
use Domain\Model\Mailing\Recipient;
use Domain\Model\Mailing\Sender;
use Domain\UseCase\ComposeMessage;
use Domain\FixturesEngine\AbstractFixture;
use Domain\FixturesEngine\FixtureInterface;
use Domain\FixturesEngine\ReferenceRepository;

class MessageFixtures extends AbstractFixture implements FixtureInterface, ComposeMessage\Responder
{
    /**
     * @var Message
     */
    private $composedMessage;

    /** {@inheritdoc} */
    public function load(ReferenceRepository $referenceRepository)
    {
        $this->loadMessage1($referenceRepository);
        $this->loadMessage2($referenceRepository);
        $this->loadMessage3($referenceRepository);
        $this->loadMessage4($referenceRepository);
        $this->loadMessage5($referenceRepository);
        $this->loadMessage6($referenceRepository);
        $this->loadMessage7($referenceRepository);
        $this->loadMessage8($referenceRepository);
        $this->loadMessage9($referenceRepository);
        $this->loadMessage10($referenceRepository);
        $this->loadMessage11($referenceRepository);
        $this->loadMessage12($referenceRepository);
    }

    /** {@inheritdoc} */
    public function getOrder()
    {
        return 3;
    }

    /** {@inheritdoc} */
    public function messageSuccessfullyComposed(Message $message)
    {
        $this->composedMessage = $message;
    }

    private function loadMessage1(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('client@testing.se')),
            new Sender(new EmailAddress('robot@lendo-example.se')),
            'Static message',
            $referenceRepository->getReference('template_2'),
            []
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_1', $this->composedMessage);
    }

    private function loadMessage2(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('olaf@schibsted.example')),
            new Sender(new EmailAddress('another@mail.example')),
            'Say hello',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Olaf']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_2', $this->composedMessage);
    }

    private function loadMessage3(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('remik@schibsted.example')),
            new Sender(new EmailAddress('another@mail.example')),
            'Say hello',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Remik']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_3', $this->composedMessage);
    }

    private function loadMessage4(ReferenceRepository $referenceRepository)
    {
        $sender = new Sender(new EmailAddress('lendo@test.se'));
        $sender->setFullName('Lendo AB');

        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('olaf@somewhere.pl')),
            $sender,
            'Welcome Olaf',
            $referenceRepository->getReference('template_3'),
            [
                'username' => 'Olaf',
                'city' => 'Gdansk',
                'weather' => 'fine',
                'weekday' => 'Sunday'
            ]
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_4', $this->composedMessage);
    }

    private function loadMessage5(ReferenceRepository $referenceRepository)
    {
        $sender = new Sender(new EmailAddress('lendo@test.se'));
        $sender->setFullName('Lendo AB');

        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('remik@somewhere.pl')),
            $sender,
            'Welcome Remik',
            $referenceRepository->getReference('template_3'),
            [
                'username' => 'Remik',
                'city' => 'Gdansk',
                'weather' => 'fine',
                'weekday' => 'Sunday'
            ]
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_5', $this->composedMessage);
    }

    private function loadMessage6(ReferenceRepository $referenceRepository)
    {
        $sender = new Sender(new EmailAddress('lendo@test.se'));
        $sender->setFullName('Lendo AB');

        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('patrick@somewhere.se')),
            $sender,
            'Welcome Patrick',
            $referenceRepository->getReference('template_3'),
            [
                'username' => 'Patrick',
                'city' => 'Stockholm',
                'weather' => 'snowing',
                'weekday' => 'Monday'
            ]
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_6', $this->composedMessage);
    }

    private function loadMessage7(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('ben@another.example')),
            new Sender(new EmailAddress('senders@mail.net')),
            'Hey Ben',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Ben']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_7', $this->composedMessage);
    }

    private function loadMessage8(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('og@schibsted.ne')),
            new Sender(new EmailAddress('another@sender.xyz')),
            'Yo',
            $referenceRepository->getReference('template_2'),
            []
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_8', $this->composedMessage);
    }

    private function loadMessage9(ReferenceRepository $referenceRepository)
    {
        $recipient = new Recipient(new EmailAddress('patrick.kluivert@aja-x.nl'));
        $recipient->setFullName('Patrick Kluivert');
        $sender = new Sender(new EmailAddress('louis.van.gaal@netherlands.xy'));
        $sender->setFullName('Louis van Gaal');

        $command = new ComposeMessage\Command(
            $recipient,
            $sender,
            'Welcome in Dutch national team',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Patrick']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_9', $this->composedMessage);
    }

    private function loadMessage10(ReferenceRepository $referenceRepository)
    {
        $recipient = new Recipient(new EmailAddress('arjen.robben@bay-ern.de'));
        $recipient->setFullName('Arjen Robben');
        $sender = new Sender(new EmailAddress('louis.van.gaal@netherlands.nl'));
        $sender->setFullName('Louis van Gaal');

        $command = new ComposeMessage\Command(
            $recipient,
            $sender,
            'Welcome in Dutch national team',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Arjen']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_10', $this->composedMessage);
    }

    private function loadMessage11(ReferenceRepository $referenceRepository)
    {
        $recipient = new Recipient(new EmailAddress('ruud.van.nisterlooy@mufc.co.uk'));
        $recipient->setFullName('Ruud van Nisterlooy');
        $sender = new Sender(new EmailAddress('louis.van.gaal@netherlands.nl'));
        $sender->setFullName('Louis van Gaal');

        $command = new ComposeMessage\Command(
            $recipient,
            $sender,
            'Welcome in Dutch national team',
            $referenceRepository->getReference('template_1'),
            ['name' => 'Ruud']
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_11', $this->composedMessage);
    }

    private function loadMessage12(ReferenceRepository $referenceRepository)
    {
        $command = new ComposeMessage\Command(
            new Recipient(new EmailAddress('bro@poland.world')),
            new Sender(new EmailAddress('one@man.army')),
            'Last fixture',
            $referenceRepository->getReference('template_2'),
            []
        );

        $composeMessageUseCase = new ComposeMessage(
            $this->eventBus,
            $this->eventStorage
        );
        $composeMessageUseCase->execute($command, $this);

        $referenceRepository->addReference('message_12', $this->composedMessage);
    }
}
