<?php

namespace Mailery\Channel\Email\Amazon\Entity;

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
     * @Cycle\Annotated\Annotation\Column(type = "string(32)")
     * @var string
     */
    protected $name;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return string|null
     */
    public function getEditRouteName(): ?string
    {
        return '/brand/settings/amazon-ses';
    }

    /**
     * @return array
     */
    public function getEditRouteParams(): array
    {
        return ['brandId' => $this->getId()];
    }

    /**
     * @return string|null
     */
    public function getViewRouteName(): ?string
    {
        return '/brand/settings/amazon-ses';
    }

    /**
     * @return array
     */
    public function getViewRouteParams(): array
    {
        return ['brandId' => $this->getId()];
    }
}