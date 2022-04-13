<?php

declare(strict_types=1);

use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Channel\Amazon\Ses\Controller\DefaultController;

return [
    Group::create('/channel')
        ->routes(
            Route::get('/amazon-ses/view/{id:\d+}')
                ->name('/channel/amazon-ses/view')
                ->action([DefaultController::class, 'view']),
            Route::methods(['GET', 'POST'], '/amazon-ses/create')
                ->action([DefaultController::class, 'create'])
                ->name('/channel/amazon-ses/create'),
            Route::methods(['GET', 'POST'], '/amazon-ses/edit/{id:\d+}')
                ->action([DefaultController::class, 'edit'])
                ->name('/channel/amazon-ses/edit'),
            Route::methods(['GET', 'POST'], '/amazon-ses/credentials/{id:\d+}')
                ->action([DefaultController::class, 'credentials'])
                ->name('/channel/amazon-ses/credentials'),
            Route::delete('/default/amazon-ses/{id:\d+}')
                ->action([DefaultController::class, 'delete'])
                ->name('/channel/amazon-ses/delete'),
        ),
];
