<?php

namespace Mailery\Channel\Amazon\Ses\Handler;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler as SmtpChannelHandler;

class ChannelHandler implements HandlerInterface
{

    /**
     * @var SmtpChannelHandler
     */
    private SmtpChannelHandler $handler;

    /**
     * @param MailerFactory $mailerFactory
     * @param MessageFactory $messageFactory
     * @param EntityManagerInterface $entityManager
     * @param WrappedUrlGenerator $wrappedUrlGenerator
     */
    public function __construct(
        MailerFactory $mailerFactory,
        MessageFactory $messageFactory,
        EntityManagerInterface $entityManager,
        WrappedUrlGenerator $wrappedUrlGenerator
    ) {
        $this->handler = new SmtpChannelHandler(
            $mailerFactory,
            $messageFactory,
            $entityManager,
            $wrappedUrlGenerator
        );
    }

    /**
     * @inheritdoc
     */
    public function withSuppressErrors(bool $suppressErrors): HandlerInterface
    {
        return $this->handler->withSuppressErrors($suppressErrors);
    }

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, \Iterator|array $recipients): bool
    {
        return $this->handler->handle($sendout, $recipients);
    }

}
