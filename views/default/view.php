<?php declare(strict_types=1);

use Mailery\Activity\Log\Widget\ActivityLogLink;
use Mailery\Icon\Icon;
use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;
use Mailery\Widget\Dataview\DetailView;
use Mailery\Widget\Link\Link;
use Mailery\Web\Widget\DateTimeFormat;
use Mailery\Web\Widget\FlashMessage;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel $channel */
/** @var Yiisoft\Yii\View\Csrf $csrf */
/** @var bool $submitted */

$this->setTitle($channel->getName());

?><div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body"><div class="flex-between-center row">
                    <div class="col-md">
                        <h3 class="mb-0">Channel #<?= $channel->getId(); ?></h1>
                        <p class="mt-1 mb-0 small">
                            Changed at <?= DateTimeFormat::widget()->dateTime($channel->getUpdatedAt())->run() ?>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-toolbar float-right">
                            <?= Link::widget()
                                ->csrf($csrf)
                                ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render() . ' Delete')
                                ->method('delete')
                                ->href($url->generate($channel->getDeleteRouteName(), $channel->getDeleteRouteParams()))
                                ->confirm('Are you sure?')
                                ->options([
                                    'class' => 'btn btn-sm btn-danger mx-sm-1',
                                ])
                                ->encode(false);
                            ?>
                            <a class="btn btn-sm btn-secondary mx-sm-1" href="<?= $url->generate($channel->getEditRouteName(), $channel->getEditRouteParams()); ?>">
                                <?= Icon::widget()->name('pencil')->options(['class' => 'mr-1']); ?>
                                Update
                            </a>
                            <b-dropdown right size="sm" variant="secondary">
                                <template v-slot:button-content>
                                    <?= Icon::widget()->name('settings'); ?>
                                </template>
                                <?= ActivityLogLink::widget()
                                    ->tag('b-dropdown-item')
                                    ->label('Activity log')
                                    ->entity($channel); ?>
                            </b-dropdown>
                            <div class="btn-toolbar float-right">
                                <a class="btn btn-sm btn-outline-secondary mx-sm-1" href="<?= $url->generate('/channel/default/index'); ?>">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= DetailView::widget()
            ->data($channel)
            ->options([
                'class' => 'table detail-view',
            ])
            ->emptyText('(not set)')
            ->emptyTextOptions([
                'class' => 'text-muted',
            ])
            ->attributes([
                [
                    'label' => 'Name',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return $data->getName();
                    },
                ],
                [
                    'label' => 'AWS Access Key ID',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return $data->getCredentials()->getKey();
                    },
                ],
                [
                    'label' => 'AWS Secret Access Key',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return str_repeat('*', strlen($data->getCredentials()->getSecret()));
                    },
                ],
                [
                    'label' => 'AWS SES region',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return $data->getCredentials()->getRegion();
                    },
                ],
            ]);
        ?>
    </div>
</div>
