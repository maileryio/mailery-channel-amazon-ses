<?php declare(strict_types=1);

use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;
use Mailery\Widget\Dataview\DetailView;

/** @var Yiisoft\Yii\WebView $this */
/** @var Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel $channel */

$this->setTitle($channel->getName());

?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <h6 class="font-weight-bold">General details</h6>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= DetailView::widget()
            ->data($channel)
            ->options([
                'class' => 'table table-top-borderless detail-view',
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
                    'label' => 'Description',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return $data->getDescription();
                    },
                ],
            ]);
        ?>
    </div>
</div>

<div class="mb-3"></div>
<div class="row">
    <div class="col-12">
        <h6 class="font-weight-bold">AWS Credentials</h6>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= DetailView::widget()
            ->data($channel)
            ->options([
                'class' => 'table table-top-borderless detail-view',
            ])
            ->emptyText('(not set)')
            ->emptyTextOptions([
                'class' => 'text-muted',
            ])
            ->attributes([
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
