<?php

namespace Mailery\Channel\Amazon\Ses\Controller;

use Mailery\Channel\Repository\ChannelRepository;
use Mailery\Channel\Amazon\Ses\Service\ChannelCrudService;
use Mailery\Channel\Amazon\Ses\Service\CredentialsCrudService;
use Mailery\Channel\Amazon\Ses\Form\ChannelForm;
use Mailery\Channel\Amazon\Ses\Form\CredentialsForm;
use Mailery\Channel\Amazon\Ses\ValueObject\ChannelValueObject;
use Mailery\Channel\Amazon\Ses\ValueObject\CredentialsValueObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Router\CurrentRoute;

class DefaultController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param ChannelRepository $channelRepo
     * @param ChannelCrudService $channelCrudService
     * @param CredentialsCrudService $credentialsCrudService
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private ChannelRepository $channelRepo,
        private ChannelCrudService $channelCrudService,
        private CredentialsCrudService $credentialsCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withControllerName('default')
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');
    }

    /**
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function view(CurrentRoute $currentRoute): Response
    {
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $content = $this->viewRenderer->renderPartialAsString('view', compact('channel'));

        return $this->viewRenderer->render('_layout', compact('channel', 'content'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param ChannelForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, ChannelForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = ChannelValueObject::fromForm($form);
            $channel = $this->channelCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate($channel->getViewRouteName(), $channel->getViewRouteParams()));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param ChannelForm $form
     * @return Response
     */
    public function edit(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, FlashInterface $flash, ChannelForm $form): Response
    {
        $body = $request->getParsedBody();
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withEntity($channel);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = ChannelValueObject::fromForm($form);
            $this->channelCrudService->update($channel, $valueObject);

            $flash->add(
                'success',
                [
                    'body' => 'Data have been saved!',
                ],
                true
            );
        }

        $content = $this->viewRenderer->renderPartialAsString('edit', compact('form'));

        return $this->viewRenderer->render('_layout', compact('channel', 'content'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param CredentialsForm $form
     * @return Response
     */
    public function credentials(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, FlashInterface $flash, CredentialsForm $form): Response
    {
        $body = $request->getParsedBody();
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withEntity($channel);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = CredentialsValueObject::fromForm($form);

            if (($credentials = $channel->getCredentials()) !== null) {
                $this->credentialsCrudService->update($credentials, $valueObject);
            } else {
                $this->credentialsCrudService->create($valueObject);
            }

            $flash->add(
                'success',
                [
                    'body' => 'Data have been saved!',
                ],
                true
            );
        }

        $content = $this->viewRenderer->renderPartialAsString('credentials', compact('form'));

        return $this->viewRenderer->render('_layout', compact('channel', 'content'));
    }

    /**
     * @param CurrentRoute $currentRoute
     * @param ChannelCrudService $channelCrudService
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function delete(CurrentRoute $currentRoute, ChannelCrudService $channelCrudService, UrlGenerator $urlGenerator): Response
    {
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $channelCrudService->delete($channel);

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(Header::LOCATION, $urlGenerator->generate('/channel/default/index'));
    }
}
