<?php

namespace Mailery\Channel\Amazon\Ses\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Campaign\Recipient\Factory\IdentificatorFactoryInterface as IdentificatorFactory;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Entity\Channel;

class AmazonSesChannelType implements ChannelTypeInterface
{
    /**
     * @param HandlerInterface $handler
     * @param RecipientIterator $recipientIterator
     * @param IdentificatorFactory $identifictorFactory
     */
    public function __construct(
        private HandlerInterface $handler,
        private RecipientIterator $recipientIterator,
        private IdentificatorFactory $identifictorFactory
    ) {}

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Amazon SES';
    }

    /**
     * @inheritdoc
     */
    public function getCreateLabel(): string
    {
        return 'Amazon SES';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteName(): ?string
    {
        return '/channel/amazon-ses/create';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteParams(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function isEntitySameType(Channel $entity): bool
    {
        return $entity->getType() === $this->getName();
    }

    /**
     * @inheritdoc
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @inheritdoc
     */
    public function getRecipientIterator(): RecipientIterator
    {
        return $this->recipientIterator;
    }

    /**
     * @inheritdoc
     */
    public function getIdentificatorFactory(): IdentificatorFactory
    {
        return $this->identifictorFactory;
    }

}
