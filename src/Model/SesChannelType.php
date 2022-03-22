<?php

namespace Mailery\Channel\Amazon\SES\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Channel\Email\Entity\EmailChannel;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Entity\Channel;

class SesChannelType implements ChannelTypeInterface
{
    /**
     * @param HandlerInterface $handler
     * @param RecipientIterator $recipientIterator
     */
    public function __construct(
        private HandlerInterface $handler,
        private RecipientIterator $recipientIterator
    ) {}

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
        return '/channel/email/create';
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
        return $entity instanceof EmailChannel;
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
}
