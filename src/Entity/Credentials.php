<?php

namespace Mailery\Channel\Amazon\Ses\Entity;

use Mailery\Common\Entity\RoutableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Channel\Amazon\Ses\Repository\CredentialsRepository;
use Mailery\Activity\Log\Mapper\LoggableMapper;
use Cycle\ORM\Entity\Behavior;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;

#[Entity(
    table: 'channel_credentials_amazon_ses',
    repository: CredentialsRepository::class,
    mapper: LoggableMapper::class,
)]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class Credentials implements RoutableEntityInterface, LoggableEntityInterface
{
    use LoggableEntityTrait;

    #[Column(type: 'primary')]
    private int $id;

    #[Column(type: 'string(255)')]
    private string $key;

    #[Column(type: 'string(255)')]
    private string $secret;

    #[Column(type: 'string(255)')]
    private string $region;

    #[Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Credentials';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return self
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return self
     */
    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteName(): ?string
    {
        return '/brand/settings/aws';
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteParams(): array
    {
        return ['brandId' => $this->getId()];
    }

    /**
     * @return string|null
     */
    public function getViewRouteName(): ?string
    {
        return '/brand/settings/aws';
    }

    /**
     * @return array
     */
    public function getViewRouteParams(): array
    {
        return ['brandId' => $this->getId()];
    }

    /**
     * @return string|null
     */
    public function getEditRouteName(): ?string
    {
        return '/brand/settings/aws';
    }

    /**
     * @return array
     */
    public function getEditRouteParams(): array
    {
        return ['brandId' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteName(): ?string
    {
        throw new \RuntimeException('Not implemented');
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteParams(): array
    {
        throw new \RuntimeException('Not implemented');
    }
}