<?php

namespace Mailery\Channel\Amazon\Ses\Handler;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Messenger\Factory\MessageFactoryInterface as MessageFactory;
use Mailery\Channel\Messenger\Factory\MessengerFactoryInterface as MessengerFactory;
use Mailery\Channel\Handler\HandlerInterface;
use Cycle\ORM\EntityManagerInterface;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Mailery\Sender\Email\Entity\EmailSender;

class ChannelHandler implements HandlerInterface
{

    /**
     * @param MessageFactory $messageFactory
     * @param MessengerFactory $messengerFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private MessageFactory $messageFactory,
        private MessengerFactory $messengerFactory,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        $recipient->setSendout($sendout);
        (new EntityWriter($this->entityManager))->write([$recipient]);

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
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

}
