<?php

namespace Mailery\Channel\Amazon\Ses\Handler;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Messenger\Factory\MessageFactoryInterface as MessageFactory;
use Mailery\Channel\Messenger\Factory\MessengerFactoryInterface as MessengerFactory;
use Mailery\Channel\Handler\HandlerInterface;
use Cycle\ORM\ORMInterface;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;

class ChannelHandler implements HandlerInterface
{

    /**
     * @param ORMInterface $orm
     * @param MessageFactory $messageFactory
     * @param MessengerFactory $messengerFactory
     */
    public function __construct(
        private ORMInterface $orm,
        private MessageFactory $messageFactory,
        private MessengerFactory $messengerFactory
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        $recipient->setSendout($sendout);
        (new EntityWriter($this->orm))->write([$recipient]);

        /** @var Campaign $campaign */
        $campaign = $sendout->getCampaign();
        /** @var EmailSender $sender */
        $sender = $campaign->getSender();
        /** @var EmailTemplate $template */
        $template = $campaign->getTemplate();

        $messenger = $this->messengerFactory
            ->withChannel($sender->getChannel())
            ->create()
        ;

        $message = $this->messageFactory
            ->withCampaign($sendout->getCampaign())
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
