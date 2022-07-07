<?php

namespace Mailery\Channel\Amazon\Ses\Messenger;

use Symfony\Component\Mailer\Mailer;
use Mailery\Channel\Messenger\MessageInterface;
use Mailery\Channel\Messenger\MessengerInterface;

class EmailMessenger implements MessengerInterface
{
    /**
     * @param Mailer $mailer
     */
    public function __construct(
        private Mailer $mailer
    ) {}

    /**
     * @inheritdoc
     */
    public function send(MessageInterface $message): void
    {
        $this->mailer->send($message->getRawMessage());
    }
}
