<?php

namespace Mailery\Channel\Amazon\Ses\Entity;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Inheritance\SingleTable;
use Mailery\Channel\Entity\Channel;
use Mailery\Common\Entity\RoutableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Cycle\Annotated\Annotation\Relation\HasOne;
use Mailery\Channel\Amazon\Ses\Model\AmazonSesChannelType;

/**
* This doc block required for STI/JTI
*/
#[Entity(
    table: 'channels',
)]
#[SingleTable(value: AmazonSesChannelType::class)]
class AmazonSesChannel extends Channel implements RoutableEntityInterface, LoggableEntityInterface
{
    use LoggableEntityTrait;

    #[HasOne(target: Credentials::class, nullable: true)]
    private ?Credentials $credentials = null;

    /**
     * @return Credentials|null
     */
    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    /**
     * @param Credentials $credentials
     * @return self
     */
    public function setCredentials(Credentials $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteName(): ?string
    {
        return '/channel/default/index';
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteParams(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getViewRouteName(): ?string
    {
        return '/channel/amazon-ses/view';
    }

    /**
     * @inheritdoc
     */
    public function getViewRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getEditRouteName(): ?string
    {
        return '/channel/amazon-ses/edit';
    }

    /**
     * @inheritdoc
     */
    public function getEditRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteName(): ?string
    {
        return '/channel/amazon-ses/delete';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteParams(): array
    {
        return ['id' => $this->getId()];
    }
}
