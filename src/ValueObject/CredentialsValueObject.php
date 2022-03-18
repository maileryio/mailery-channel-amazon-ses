<?php

namespace Mailery\Channel\Email\Amazon\ValueObject;

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
     * @param TemplateForm $form
     * @return self
     */
    public static function fromForm(SettingsForm $form): self
    {
        $new = new self();
        $new->key = $form->getKey();
        $new->secret = $form->getSecret();
        $new->region = $form->getRegion();

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
}
