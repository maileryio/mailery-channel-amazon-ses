<?php

namespace Mailery\Channel\Email\Amazon\Validator;

use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule;
use Yiisoft\Validator\ValidationContext;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
use Aws\Credentials\Credentials as AwsCredentials;

class CheckSesConnection extends Rule
{
    /**
     * @param type $value
     * @param ValidationContext $context
     * @return Result
     */
    protected function validateValue($value, ValidationContext $context = null): Result
    {
        $validateResult = new Result();
        $sesClient = new SesClient([
            'version' => '2010-12-01',
            'region' => $context->getDataSet()->getAttributeValue('region'),
            'credentials' => new AwsCredentials(
                $context->getDataSet()->getAttributeValue('key'),
                $context->getDataSet()->getAttributeValue('secret')
            ),
            'http'    => [
                'connect_timeout' => 5,
            ],
        ]);

        try {
            $sesClient->listVerifiedEmailAddresses();
        } catch (SesException $e) {
            if ($e->getPrevious() !== null) {
                $errorMessage = $e->getPrevious()->getMessage();
            } else {
                $errorMessage = $e->getMessage();
            }

            $validateResult->addError($errorMessage);
        }

        return $validateResult;
    }
}