<?php

use Mailery\Channel\Amazon\Ses\Mailer\MailerFactory;
use Mailery\Channel\Amazon\Ses\Mailer\MessageFactory;
use Mailery\Channel\Amazon\Ses\Model\RegionList;
use Mailery\Channel\Amazon\Ses\Model\AmazonSesChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Amazon\Ses\Handler\ChannelHandler;
use Mailery\Channel\Amazon\Ses\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesTransportFactory;
use Yiisoft\Definitions\Reference;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    MailerFactory::class => [
        'class' => MailerFactory::class,
        '__construct()' => [
            'transportFactory' => Reference::to(SesTransportFactory::class),
        ],
    ],

    MessageFactory::class => [
        'class' => MessageFactory::class,
        '__construct()' => [
            'message' => Reference::to(Email::class),
        ],
    ],

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
