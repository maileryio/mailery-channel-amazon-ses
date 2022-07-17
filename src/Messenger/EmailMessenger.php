<?php

namespace Mailery\Channel\Amazon\Ses\Messenger;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Mailery\Messenger\MessageInterface;
use Mailery\Messenger\MessengerInterface;
use Mailery\Messenger\Exception\MessengerException;

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
        try {
            $this->mailer->send($message->getRawMessage());
        } catch (\Exception $e) {
            $error = new MessengerException($e->getMessage(), $e->getCode(), $e);

            if ($e instanceof TransportExceptionInterface) {
                $error->setUserMessage($e->getMessage());
            }

            throw $error;
        }
    }
}
