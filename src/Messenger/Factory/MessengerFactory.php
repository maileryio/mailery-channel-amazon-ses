<?php

namespace Mailery\Channel\Amazon\Ses\Messenger\Factory;

use Mailery\Messenger\MessengerInterface;
use Mailery\Messenger\Factory\MessengerFactoryInterface;
use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel as Channel;
use Mailery\Channel\Amazon\Ses\Messenger\EmailMessenger;
use Yiisoft\Factory\Factory;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesTransportFactory;

class MessengerFactory implements MessengerFactoryInterface
{

    /**
     * @var Channel
     */
    private Channel $channel;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private ContainerInterface $container
    ) {}

    /**
     * @inheritdoc
     */
    public function withChannel(object $channel): self
    {
        $new = clone $this;
        $new->channel = $channel;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function create(): MessengerInterface
    {
        $credentials = $this->channel->getCredentials();

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

        $factory = new Factory(
            $this->container,
            [
                TransportInterface::class => fn() => $this->container->get(SesTransportFactory::class)->create($dsn),
            ]
        );

        return new EmailMessenger(
            $factory->create(Mailer::class)
        );
    }

}
