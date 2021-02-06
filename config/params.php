<?php

use Yiisoft\Router\UrlGeneratorInterface;

return [
    'maileryio/mailery-brand' => [
        'settings-menu' => [
            'items' => [
                'aws-settings' => [
                    'label' => static function () {
                        return 'AWS Credentials';
                    },
                    'url' => static function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/brand/settings/aws');
                    },
                ],
            ],
        ],
    ],
];
