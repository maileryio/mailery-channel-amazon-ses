<?php

namespace Mailery\Channel\Amazon\Ses\Handler;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelHandler implements HandlerInterface
{

    /**
     * @var bool
     */
    private bool $suppressErrors = false;

    /**
     * @param MailerFactory $mailerFactory
     * @param MessageFactory $messageFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private MailerFactory $mailerFactory,
        private MessageFactory $messageFactory,
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
        /** @var Campaign $campaign */
        $campaign = $sendout->getCampaign();

        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        try {
            $mailer = $this->mailerFactory->create($sender->getChannel());
            $message = $this->messageFactory->create($campaign, $recipient);

            $mailer->send($message);
        } catch (\Exception $e) {
            $recipient->setError($e->getMessage());
            throw $e;
        } finally {
            $recipient->setSent(true);
            $recipient->setSendout($sendout);

            (new EntityWriter($this->entityManager))->write([$recipient]);
        }

        return true;
    }

}
