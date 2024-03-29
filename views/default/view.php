<?php declare(strict_types=1);

use Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel;
use Mailery\Channel\Amazon\Ses\Entity\Credentials;
use Yiisoft\Yii\Widgets\ContentDecorator;
use Yiisoft\Yii\DataView\DetailView;
use Yiisoft\Html\Html;
use Mailery\Web\Vue\Directive;

/** @var Yiisoft\Yii\WebView $this */
/** @var Mailery\Channel\Amazon\Ses\Entity\AmazonSesChannel $channel */

$this->setTitle($channel->getName());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-channel-amazon-ses/views/default/_layout.php')
    ->parameters(compact('channel', 'csrf'))
    ->begin(); ?>

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
            ->model($channel)
            ->options([
                'class' => 'table table-top-borderless detail-view',
            ])
            ->emptyValue('<span class="text-muted">(not set)</span>')
            ->attributes([
                [
                    'label' => 'Name',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return Directive::pre($data->getName());
                    },
                ],
                [
                    'label' => 'Description',
                    'value' => function (AmazonSesChannel $data, $index) {
                        return Directive::pre($data->getDescription());
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
        <?php if (($credentials = $channel->getCredentials()) !== null) {
            echo DetailView::widget()
                ->model($credentials)
                ->options([
                    'class' => 'table table-top-borderless detail-view',
                ])
                ->emptyValue('<span class="text-muted">(not set)</span>')
                ->attributes([
                    [
                        'label' => 'AWS Access Key ID',
                        'value' => function (Credentials $data, $index) {
                            return $data->getKey();
                        },
                    ],
                    [
                        'label' => 'AWS Secret Access Key',
                        'value' => function (Credentials $data, $index) {
                            return str_repeat('*', strlen($data->getSecret()));
                        },
                    ],
                    [
                        'label' => 'AWS SES region',
                        'value' => function (Credentials $data, $index) {
                            return Directive::pre($data->getRegion());
                        },
                    ],
                ]);
        } else { ?>
            <div class="alert alert-danger" role="alert">
                <?= sprintf(
                    'Amazon AWS credentials are not configured. Please configure settings on the %s tab.',
                    Html::a('AWS Credentials', $url->generate('/channel/amazon-ses/credentials', ['id' => $channel->getId()]))
                ); ?>
            </div>
        <?php } ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
