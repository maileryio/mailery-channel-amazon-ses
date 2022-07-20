<?php

namespace Mailery\Channel\Amazon\Ses\Handler;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Messenger\Exception\MessengerException;
use Mailery\Messenger\Factory\MessageFactoryInterface;
use Mailery\Messenger\Factory\MessengerFactoryInterface;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Messenger\Stamp\ChannelStamp;
use Mailery\Sender\Email\Entity\EmailSender;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class ChannelHandler implements HandlerInterface
{

    /**
     * @var bool
     */
    private bool $suppressErrors = false;

    /**
     * @param MessageFactoryInterface $messageFactory
     * @param MessengerFactoryInterface $messengerFactory
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private MessageFactoryInterface $messageFactory,
        private MessengerFactoryInterface $messengerFactory,
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @inheritdoc
     */
    public function withSuppressErrors(bool $suppressErrors): self
    {
        $new = clone $this;
        $new->suppressErrors = $suppressErrors;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        $recipient->setSent(true);
        $recipient->setSendout($sendout);

        /** @var Campaign $campaign */
        $campaign = $sendout->getCampaign();
        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        $messenger = $this->messengerFactory
            ->withChannel($sender->getChannel())
            ->create()
        ;

        $message = $this->messageFactory
            ->withCampaign($campaign)
            ->withRecipient($recipient)
            ->create()
        ;

        $envelope = $this->messageBus->dispatch(
            new SendEmailMessage($message->getRawMessage()),
            [
                new ChannelStamp($sender->getChannel()),
            ]
        );

        $receivedStamp = $envelope->last(ReceivedStamp::class);
        $transportName = $receivedStamp?->getTransportName();

        $sentStamp = $envelope->last(SentStamp::class);
        $senderAlias = $sentStamp?->getSenderAlias();
        $senderClass = $sentStamp?->getSenderClass();

        $handledStamp = $envelope->last(HandledStamp::class);
        $result = $handledStamp?->getResult();

        var_dump($transportName, $senderAlias, $senderClass, $result);exit;


        try {
            $messenger->send($message);
        } catch (MessengerException $e) {
            $recipient->setError($e->getUserMessage());
            throw $e;
        } finally {
            (new EntityWriter($this->entityManager))->write([$recipient]);
        }

        return true;
    }

}
