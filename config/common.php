<?php

use Mailery\Channel\Amazon\Ses\Mailer\SesDsnFactory;
use Mailery\Channel\Amazon\Ses\Model\RegionList;
use Mailery\Channel\Amazon\Ses\Model\AmazonSesChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
use Mailery\Channel\Smtp\Mailer\Message\EmailMessage;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Amazon\Ses\Handler\ChannelHandler;
use Mailery\Channel\Amazon\Ses\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesTransportFactory;
use Yiisoft\Definitions\Reference;
use Yiisoft\Definitions\DynamicReference;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    AmazonSesChannelType::class => static function (ChannelHandler $handler, SubscriberRepository $subscriberRepo) {
        return new AmazonSesChannelType(
            $handler,
            new RecipientIterator(new RecipientFactory(), $subscriberRepo),
            new IdentificatorFactory()
        );
    },

    ChannelHandler::class => [
        'class' => ChannelHandler::class,
        '__construct()' => [
            'mailerFactory' => DynamicReference::to([
                'class' => MailerFactory::class,
                '__construct()' => [
                    'dsnFactory' => Reference::to(SesDsnFactory::class),
                    'transportFactory' => Reference::to(SesTransportFactory::class),
                ],
            ]),
            'messageFactory' => DynamicReference::to([
                'class' => MessageFactory::class,
                '__construct()' => [
                    'message' => Reference::to(EmailMessage::class),
                ],
            ]),
        ],
    ],

    CredentialsRepository::class => static function (ORMInterface $orm) {
        return $orm->getRepository(Credentials::class);
    },
];
