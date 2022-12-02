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
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\ContextObserver;
use Mailery\Template\Email\Renderer\WrappedUrlGenerator;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Yiisoft\Router\UrlGeneratorInterface;

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
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        private MailerFactory $mailerFactory,
        private MessageFactory $messageFactory,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator
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

        $recipient->setSendout($sendout);
        (new EntityWriter($this->entityManager))->write([$recipient]);

        try {
            $observer = new ContextObserver();
            $context = (new Context([
                'url' => new WrappedUrlGenerator($this->urlGenerator, $recipient),
            ]))->withObserver($observer);

            $mailer = $this->mailerFactory->create($sender->getChannel());
            $message = $this->messageFactory
                ->withContext($context)
                ->create($campaign, $recipient);

            $mailer->send($message);
        } catch (\Exception $e) {
            $recipient->setError($e->getMessage());
            throw $e;
        } finally {
            $recipient->setSent(true);
            (new EntityWriter($this->entityManager))->write([$recipient]);
        }

        return true;
    }

}
