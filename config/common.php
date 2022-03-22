<?php

use Mailery\Channel\Amazon\SES\Model\RegionList;
use Mailery\Channel\Amazon\SES\Model\SesChannelType;
use Psr\Container\ContainerInterface;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Email\Factory\RecipientFactory;
use Mailery\Channel\Email\Handler\EmailChannelHandler;

return [
    RegionList::class => static function () use($params) {
        return new RegionList($params['maileryio/mailery-channel-amazon-ses']['elements']);
    },

    SesChannelType::class =>  static function (ContainerInterface $container) {
        return new SesChannelType(
            $container->get(EmailChannelHandler::class),
            new RecipientIterator(new RecipientFactory())
        );
    },
];
