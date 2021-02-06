<?php

declare(strict_types=1);

namespace Mailery\Channel\Email\Aws\Form;

use Mailery\Brand\Entity\Brand;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Form\HtmlOptions\HasLengthHtmlOptions;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\InRange;
use Mailery\Brand\Repository\BrandRepository;
use Mailery\Channel\Model\ChannelList;
use Mailery\Channel\ChannelInterface;

class SettingsForm extends FormModel
{
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
     * @var Brand
     */
    private ?Brand $brand = null;

    /**
     * @var BrandRepository
     */
    private BrandRepository $brandRepo;

    /**
     * @var ChannelList
     */
    private ChannelList $channelList;

    /**
     * @param BrandRepository $brandRepo
     * @param ChannelList $channelList
     */
    public function __construct(BrandRepository $brandRepo, ChannelList $channelList)
    {
        $this->brandRepo = $brandRepo;
        $this->channelList = $channelList;
        parent::__construct();
    }

    /**
     * @param string $name
     * @param type $value
     * @return void
     */
    public function setAttribute(string $name, $value): void
    {
        if ($name === 'channels') {
            $this->$name = array_filter((array) $value);
        } else {
            parent::setAttribute($name, $value);
        }
    }

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;
        $new->name = $brand->getName();
        $new->description = $brand->getDescription();
        $new->channels = $brand->getChannels();

        return $new;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Brand name',
            'description' => 'Description (optional)',
            'channels' => 'Channels for this brand',
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return 'BrandForm';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                new RequiredHtmlOptions(new Required()),
                new HasLengthHtmlOptions((new HasLength())->min(4)->max(32)),
                new Callback(function ($value) {
                    $result = new Result();
                    $brand = $this->brandRepo->findByName($value, $this->brand);

                    if ($brand !== null) {
                        $result->addError('This brand name already exists.');
                    }

                    return $result;
                })
            ],
            'channels' => [
                new RequiredHtmlOptions(new Required()),
                new InRange(array_keys($this->getChannelListOptions())),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getChannelListOptions(): array
    {
        $listOptions = [];
        foreach ($this->channelList as $channel) {
            /** @var ChannelInterface $channel */
            $listOptions[$channel->getKey()] = $channel->getLabel();
        }

        return $listOptions;
    }
}
