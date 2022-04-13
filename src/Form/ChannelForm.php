<?php

namespace Mailery\Channel\Amazon\Ses\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;
use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;

class ChannelForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @param AmazonSesChannel $channel
     * @return self
     */
    public function withEntity(AmazonSesChannel $channel): self
    {
        $new = clone $this;
        $new->name = $channel->getName();
        $new->description = $channel->getDescription();

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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
            'description' => 'Description (optional)',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'name' => [
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
            ],
        ];
    }

}
