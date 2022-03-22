<?php

namespace Mailery\Channel\Amazon\SES\Controller;

use Mailery\Channel\Amazon\SES\Form\SettingsForm;
use Mailery\Channel\Amazon\SES\Service\CredentialsCrudService;
use Mailery\Channel\Amazon\SES\Repository\CredentialsRepository;
use Mailery\Channel\Amazon\SES\ValueObject\CredentialsValueObject;
use Mailery\Brand\BrandLocatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Yii\View\ViewRenderer;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;

class SettingsController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param CredentialsCrudService $credentialsCrudService
     * @param CredentialsRepository $credentialsRepo
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private CredentialsCrudService $credentialsCrudService,
        private CredentialsRepository $credentialsRepo,
        BrandLocatorInterface $brandLocator
    ) {
        $this->viewRenderer = $viewRenderer
            ->withControllerName('settings')
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->credentialsRepo = $credentialsRepo->withBrand($brandLocator->getBrand());
        $this->credentialsCrudService = $credentialsCrudService->withBrand($brandLocator->getBrand());
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param SettingsForm $form
     * @return Response
     */
    public function index(Request $request, ValidatorInterface $validator, FlashInterface $flash, SettingsForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($credentials = $this->credentialsRepo->findOne()) !== null) {
            $form = $form->withEntity($credentials);
        }

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = CredentialsValueObject::fromForm($form);

            if ($credentials !== null) {
                $this->credentialsCrudService->update($credentials, $valueObject);
            } else {
                $this->credentialsCrudService->create($valueObject);
            }

            $flash->add(
                'success',
                [
                    'body' => 'Settings have been saved!',
                ],
                true
            );
        }

        return $this->viewRenderer->render('index', compact('form'));
    }
}
