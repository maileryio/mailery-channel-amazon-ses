<?php

use Mailery\Channel\Amazon\SES\Model\RegionList;
use Mailery\Channel\Amazon\SES\Model\ChannelType;
use Psr\Container\ContainerInterface;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Email\Factory\RecipientFactory;
use Mailery\Channel\Email\Handler\ChannelHandler;
use Mailery\Channel\Amazon\SES\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\SES\Entity\Credentials;
use Cycle\ORM\ORMInterface;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    ChannelType::class =>  static function (ContainerInterface $container) {
        return new ChannelType(
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
