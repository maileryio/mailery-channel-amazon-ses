<?php

use Mailery\Channel\Amazon\Ses\Model\RegionList;
use Mailery\Channel\Amazon\Ses\Model\AmazonSesChannelType;
use Psr\Container\ContainerInterface;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Smtp\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler;
use Mailery\Channel\Amazon\Ses\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Cycle\ORM\ORMInterface;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    AmazonSesChannelType::class =>  static function (ContainerInterface $container) {
        return new AmazonSesChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(new RecipientFactory())
        );
    },

    CredentialsRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Credentials::class);
    },
];
