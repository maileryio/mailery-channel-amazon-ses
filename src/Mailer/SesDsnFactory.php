<?php

namespace Mailery\Channel\Amazon\Ses\Mailer;

use Mailery\Channel\Entity\Channel;
use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;
use Mailery\Channel\Smtp\Mailer\DsnFactoryInterface;
use Symfony\Component\Mailer\Transport\Dsn;

class SesDsnFactory implements DsnFactoryInterface
{

    /**
     * @param Channel $channel
     * @return Dsn
     */
    public function create(Channel $channel): Dsn
    {
        if (!$channel instanceof AmazonSesChannel) {
            throw new \RuntimeException(sprintf('The channel must be instance of %s', AmazonSesChannel::class));
        }

        $credentials = $channel->getCredentials();

        return new Dsn(
            'ses',
            'default',
            $credentials->getKey(),
            $credentials->getSecret(),
            null,
            [
                'region' => $credentials->getRegion(),
            ]
        );
    }

}
