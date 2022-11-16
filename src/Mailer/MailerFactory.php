<?php

namespace Mailery\Channel\Amazon\Ses\Mailer;

use Mailery\Channel\Entity\Channel;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

class MailerFactory
{

    /**
     * @param TransportFactoryInterface $transportFactory
     */
    public function __construct(
        private TransportFactoryInterface $transportFactory
    ) {}

    /**
     * @param Channel $channel
     * @return MailerInterface
     */
    public function create(Channel $channel): MailerInterface
    {
        $credentials = $channel->getCredentials();

        $dsn = new Dsn(
            'ses',
            'default',
            $credentials->getKey(),
            $credentials->getSecret(),
            null,
            [
                'region' => $credentials->getRegion(),
            ]
        );

        return new Mailer(
            $this->transportFactory->create($dsn)
        );
    }

}
