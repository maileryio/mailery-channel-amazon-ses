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
use Symfony\Component\Messenger\MessageBusInterface;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    ChannelHandler::class => static function (ContainerInterface $container, MessageBusInterface $bus, EntityManagerInterface $entityManager) {
        return new ChannelHandler(
            new MessageFactory(EmailMessage::class),
            new MessengerFactory($container),
            $bus,
            $entityManager
        );
    },

    AmazonSesChannelType::class => static function (ChannelHandler $handler, SubscriberRepository $subscriberRepo) {
        return new AmazonSesChannelType(
            $handler,
            new RecipientIterator(new RecipientFactory(), $subscriberRepo),
            new IdentificatorFactory()
        );
    },

    CredentialsRepository::class => static function (ORMInterface $orm) {
        return $orm->getRepository(Credentials::class);
    },
];
