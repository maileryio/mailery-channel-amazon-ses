<?php

namespace Mailery\Channel\Amazon\Ses\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Mailery\Channel\Amazon\Ses\ValueObject\CredentialsValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Mailery\Brand\Entity\Brand;

class CredentialsCrudService
{
    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;

        return $new;
    }

    /**
     * @param CredentialsValueObject $valueObject
     * @return Credentials
     */
    public function create(CredentialsValueObject $valueObject): Credentials
    {
        $credentials = (new Credentials())
            ->setBrand($this->brand)
            ->setKey($valueObject->getKey())
            ->setSecret($valueObject->getSecret())
            ->setRegion($valueObject->getRegion())
        ;

        (new EntityWriter($this->entityManager))->write([$credentials]);

        return $credentials;
    }

    /**
     * @param Credentials $credentials
     * @param CredentialsValueObject $valueObject
     * @return Credentials
     */
    public function update(Credentials $credentials, CredentialsValueObject $valueObject): Credentials
    {
        $credentials = $credentials
            ->setKey($valueObject->getKey())
            ->setSecret($valueObject->getSecret())
            ->setRegion($valueObject->getRegion())
        ;

        (new EntityWriter($this->entityManager))->write([$credentials]);

        return $credentials;
    }

    /**
     * @param Credentials $credentials
     * @return void
     */
    public function delete(Credentials $credentials): void
    {
        (new EntityWriter($this->entityManager))->delete([$credentials]);
    }
}