<?php

namespace Mailery\Channel\Email\Amazon\Provider;

use Psr\Container\ContainerInterface;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Channel\Email\Amazon\Controller\SettingsController;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function register(ContainerInterface $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create('/brand/{brandId:\d+}')
                ->routes(
                    Route::methods(['GET', 'POST'], '/settings/aws')
                        ->name('/brand/settings/aws')
                        ->action([SettingsController::class, 'ses'])
                )
        );
    }
}
