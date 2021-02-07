<?php

declare(strict_types=1);

namespace Mailery\Channel\Email\Amazon\Form;

use Mailery\Brand\Entity\Brand;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\InRange;
use Mailery\Brand\Repository\BrandRepository;
use Mailery\Channel\Email\Amazon\Model\RegionList;

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
     * @var RegionList
     */
    private RegionList $regionList;

    /**
     * @param BrandRepository $brandRepo
     * @param RegionList $regionList
     */
    public function __construct(BrandRepository $brandRepo, RegionList $regionList)
    {
        $this->brandRepo = $brandRepo;
        $this->regionList = $regionList;
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
//        $new->name = $brand->getName();
//        $new->description = $brand->getDescription();
//        $new->channels = $brand->getChannels();

        return $new;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'key' => 'AWS Access Key',
            'secret' => 'AWS Access Secret',
            'region' => 'Amazon SES region',
        ];
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        return [
            'region' => 'Select your Amazon SES region. Please select the same region as what\'s set in your Amazon SES console in the region selection drop down menu at the top right.',
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return 'SettingsForm';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'key' => [
                new RequiredHtmlOptions(new Required()),
            ],
            'secret' => [
                new RequiredHtmlOptions(new Required()),
            ],
            'region' => [
                new RequiredHtmlOptions(new Required()),
                new InRange(array_keys($this->getRegionListOptions())),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getRegionListOptions(): array
    {
        return $this->regionList->toArray();
    }
}
