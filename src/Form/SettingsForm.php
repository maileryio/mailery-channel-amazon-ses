<?php

declare(strict_types=1);

namespace Mailery\Channel\Email\Amazon\Form;

use Mailery\Channel\Email\Amazon\Entity\Credentials;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\InRange;
use Mailery\Channel\Email\Amazon\Model\RegionList;
use Mailery\Channel\Email\Amazon\Validator\CheckSesConnection;

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
     * @var RegionList
     */
    private RegionList $regionList;

    /**
     * @param RegionList $regionList
     */
    public function __construct(RegionList $regionList)
    {
        $this->regionList = $regionList;
        parent::__construct();
    }

    /**
     * @param Credentials $credentials
     * @return self
     */
    public function withEntity(Credentials $credentials): self
    {
        $new = clone $this;
        $new->key = $credentials->getKey();
        $new->secret = $credentials->getSecret();
        $new->region = $credentials->getRegion();

        return $new;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'key' => 'AWS Access Key ID',
            'secret' => 'AWS Secret Access Key',
            'region' => 'Your Amazon SES region',
        ];
    }

    /**
     * @return array
     */
    public function getAttributeHints(): array
    {
        return [
            'region' => 'Select your Amazon SES region. Please select the same region as what\'s set in your Amazon SES console in the region selection drop down menu at the top right.',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
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
                new CheckSesConnection(),
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
