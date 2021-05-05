<?php

namespace Mailery\Channel\Email\Amazon\Entity;

use Mailery\Brand\Entity\Brand;
use Mailery\Common\Entity\RoutableEntityInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "channel_credentials_amazon_ses",
 *      repository = "Mailery\Channel\Email\Amazon\Repository\CredentialsRepository",
 *      mapper = "Mailery\Channel\Email\Amazon\Mapper\DefaultMapper"
 * )
 */
class Credentials implements RoutableEntityInterface
{
    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    protected $id;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    protected $key;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    protected $secret;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    protected $region;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Brand\Entity\Brand", nullable = false)
     * @var Brand
     */
    protected $brand;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ? (string) $this->id : null;
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
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return self
     */
    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

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