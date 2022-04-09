<?php

namespace Mailery\Channel\Amazon\Ses\ValueObject;

use Mailery\Channel\Amazon\Ses\Form\ChannelForm;

class ChannelValueObject
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $key = null;

    /**
     * @var string|null
     */
    private ?string $secret = null;

    /**
     * @var string|null
     */
    private ?string $region = null;

    /**
     * @param ChannelForm $form
     * @return self
     */
    public static function fromForm(ChannelForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();
        $new->key = $form->getKey();
        $new->secret = $form->getSecret();
        $new->region = $form->getRegion();

        return $new;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
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
