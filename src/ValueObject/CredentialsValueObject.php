<?php

namespace Mailery\Channel\Email\Amazon\ValueObject;

use Mailery\Brand\Entity\Brand;
use Mailery\Channel\Email\Amazon\Form\SettingsForm;

class CredentialsValueObject
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $secret;

    /**
     * @var string
     */
    private string $region;

    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param TemplateForm $form
     * @return self
     */
    public static function fromForm(SettingsForm $form): self
    {
        $new = new self();

        $new->key = $form->getAttributeValue('key');
        $new->secret = $form->getAttributeValue('secret');
        $new->region = $form->getAttributeValue('region');

        return $new;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
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
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;

        return $new;
    }
}
