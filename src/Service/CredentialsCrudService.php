<?php

namespace Mailery\Channel\Email\Amazon\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Channel\Email\Amazon\Entity\Credentials;
use Mailery\Channel\Email\Amazon\ValueObject\CredentialsValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class CredentialsCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @param CredentialsValueObject $valueObject
     * @return Credentials
     */
    public function create(CredentialsValueObject $valueObject): Credentials
    {
        $credentials = (new Credentials())
            ->setKey($valueObject->getKey())
            ->setSecret($valueObject->getSecret())
            ->setRegion($valueObject->getRegion())
            ->setBrand($valueObject->getBrand())
        ;

        (new EntityWriter($this->orm))->write([$credentials]);

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

        (new EntityWriter($this->orm))->write([$credentials]);

        return $credentials;
    }

    /**
     * @param Credentials $credentials
     * @return void
     */
    public function delete(Credentials $credentials): void
    {
        (new EntityWriter($this->orm))->delete([$credentials]);
    }
}