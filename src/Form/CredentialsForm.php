<?php

namespace Mailery\Channel\Amazon\Ses\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\InRange;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Mailery\Channel\Amazon\Ses\Model\RegionList;
use Mailery\Channel\Amazon\Ses\Validator\CheckSesConnection;

class CredentialsForm extends FormModel
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
     * @param RegionList $regionList
     */
    public function __construct(
        private RegionList $regionList
    ) {
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
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return string|null
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'key' => 'AWS Access Key ID',
            'secret' => 'AWS Secret Access Key',
            'region' => 'Amazon SES region',
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
                Required::rule(),
            ],
            'secret' => [
                Required::rule(),
            ],
            'region' => [
                Required::rule(),
                InRange::rule(array_keys($this->getRegionListOptions())),
                CheckSesConnection::rule(),
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
