<?php

use Cycle\ORM\EntityManagerInterface;
use Mailery\Channel\Amazon\Ses\Model\RegionList;
use Mailery\Channel\Amazon\Ses\Model\AmazonSesChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Amazon\Ses\Messenger\EmailMessage;
use Mailery\Channel\Amazon\Ses\Messenger\Factory\MessageFactory;
use Mailery\Channel\Amazon\Ses\Messenger\Factory\MessengerFactory;
use Mailery\Channel\Amazon\Ses\Handler\ChannelHandler;
use Mailery\Channel\Amazon\Ses\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Psr\Container\ContainerInterface;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    ChannelHandler::class => static function (ContainerInterface $container) {
        return new ChannelHandler(
            new MessageFactory(EmailMessage::class),
            new MessengerFactory($container),
            $container->get(EntityManagerInterface::class)
        );
    },

    AmazonSesChannelType::class => static function (ContainerInterface $container) {
        return new AmazonSesChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(
                new RecipientFactory(),
                $container->get(SubscriberRepository::class)
            ),
            new IdentificatorFactory()
        );
    },

    CredentialsRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Credentials::class);
    },
];
