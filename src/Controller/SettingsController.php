<?php

namespace Mailery\Channel\Email\Amazon\Controller;

use Mailery\Channel\Email\Amazon\Form\SettingsForm;
use Mailery\Channel\Email\Amazon\Service\CredentialsCrudService;
use Mailery\Channel\Email\Amazon\Repository\CredentialsRepository;
use Mailery\Channel\Email\Amazon\ValueObject\CredentialsValueObject;
use Mailery\Brand\BrandLocatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;

class SettingsController
{
    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @var CredentialsCrudService
     */
    private CredentialsCrudService $credentialsCrudService;

    /**
     * @var CredentialsRepository
     */
    private CredentialsRepository $credentialsRepository;

    /**
     * @var BrandLocatorInterface
     */
    private BrandLocatorInterface $brandLocator;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param CredentialsCrudService $credentialsCrudService
     * @param CredentialsRepository $credentialsRepository
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        CredentialsCrudService $credentialsCrudService,
        CredentialsRepository $credentialsRepository,
        BrandLocatorInterface $brandLocator
    ) {
        $this->viewRenderer = $viewRenderer
            ->withControllerName('settings')
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->credentialsCrudService = $credentialsCrudService;
        $this->credentialsRepository = $credentialsRepository;
        $this->brandLocator = $brandLocator;
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param SettingsForm $form
     * @return Response
     */
    public function ses(Request $request, ValidatorInterface $validator, FlashInterface $flash, SettingsForm $form): Response
    {
        $body = $request->getParsedBody();
        $brand = $this->brandLocator->getBrand();

        $credentials = $this->credentialsRepository
            ->withBrand($brand)
            ->findOne();

        if ($credentials !== null) {
            $form = $form->withEntity($credentials);
        }

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)) {
            $valueObject = CredentialsValueObject::fromForm($form)
                ->withBrand($brand);

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

        return $this->viewRenderer->render('ses', compact('form'));
    }
}
