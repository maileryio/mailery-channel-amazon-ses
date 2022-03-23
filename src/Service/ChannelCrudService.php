<?php

namespace Mailery\Channel\Amazon\SES\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Channel\Amazon\SES\Entity\AmazonSesChannel;
use Mailery\Channel\Amazon\SES\ValueObject\ChannelValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelCrudService
{
    /**
     * @param ORMInterface $orm
     */
    public function __construct(
        private ORMInterface $orm
    ) {}

    /**
     * @param ChannelValueObject $valueObject
     * @return AmazonSesChannel
     */
    public function create(ChannelValueObject $valueObject): AmazonSesChannel
    {
        $channel = (new AmazonSesChannel())
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param AmazonSesChannel $channel
     * @param ChannelValueObject $valueObject
     * @return AmazonSesChannel
     */
    public function update(AmazonSesChannel $channel, ChannelValueObject $valueObject): AmazonSesChannel
    {
        $channel = $channel
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param AmazonSesChannel $channel
     * @return bool
     */
    public function delete(AmazonSesChannel $channel): bool
    {
        (new EntityWriter($this->orm))->delete([$channel]);

        return true;
    }
}
