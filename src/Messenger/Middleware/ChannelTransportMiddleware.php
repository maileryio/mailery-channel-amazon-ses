<?php

namespace Mailery\Channel\Amazon\Ses\Messenger\Middleware;

use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;
use Mailery\Channel\Messenger\Stamp\ChannelStamp;
use Mailery\Channel\Messenger\Stamp\TransportStamp;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesTransportFactory;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class ChannelTransportMiddleware implements MiddlewareInterface
{

    /**
     * @param SesTransportFactory $transportFactory
     */
    public function __construct(
        private SesTransportFactory $transportFactory
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (($channelStamp = $envelope->last(ChannelStamp::class)) !== null
            && $channelStamp->getChannel() instanceof AmazonSesChannel
        ) {
            /** @var AmazonSesChannel $channel */
            $channel = $channelStamp->getChannel();
            $credentials = $channel->getCredentials();

            $transport = $this->transportFactory->create(new Dsn(
                'ses',
                'default',
                $credentials->getKey(),
                $credentials->getSecret(),
                null,
                [
                    'region' => $credentials->getRegion(),
                ]
            ));

            $envelope = $envelope->with(new TransportStamp($transport));
        }

        return $stack->next()->handle($envelope, $stack);
    }

}
