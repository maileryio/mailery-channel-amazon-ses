<?php

use Cycle\ORM\ORMInterface;
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
use Psr\Container\ContainerInterface;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    ChannelHandler::class => static function (ContainerInterface $container) {
        return new ChannelHandler(
            $container->get(ORMInterface::class),
            new MessageFactory(EmailMessage::class),
            new MessengerFactory($container)
        );
    },

    AmazonSesChannelType::class => static function (ContainerInterface $container) {
        return new AmazonSesChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(new RecipientFactory()),
            new IdentificatorFactory()
        );
    },

    CredentialsRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Credentials::class);
    },
];
