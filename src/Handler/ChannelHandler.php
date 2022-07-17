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
use Mailery\Sender\Email\Entity\EmailSender;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelHandler implements HandlerInterface
{

    /**
     * @var bool
     */
    private bool $suppressErrors = false;

    /**
     * @param MessageFactoryInterface $messageFactory
     * @param MessengerFactoryInterface $messengerFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private MessageFactoryInterface $messageFactory,
        private MessengerFactoryInterface $messengerFactory,
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
