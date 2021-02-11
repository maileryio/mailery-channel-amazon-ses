<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Brand\Entity\Brand $brand */
/** @var Mailery\Channel\Email\Amazon\Form\SettingsForm $form */
/** @var string $csrf */
?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-brand/views/settings/_layout.php')
    ->begin(); ?>

<div class="mb-5"></div>
<div class="row">
    <div class="col-6 col-xl-4">
        <?= FlashMessage::widget(); ?>
    </div>
</div>

<div class="row">
    <div class="col-6 col-xl-4">
        <?= Form::widget()
            ->action($urlGenerator->generate('/brand/settings/aws'))
            ->options(
                [
                    'id' => 'form-brand',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin(); ?>

        <h3 class="h6">Amazon Web Services Credentials</h3>
        <div class="mb-4"></div>

        <?= $field->config($form, 'key'); ?>
        <?= $field->config($form, 'secret'); ?>

        <div class="mb-5"></div>
        <h3 class="h6">Amazon SES Region</h3>
        <div class="mb-4"></div>

        <?= $field->config($form, 'region')
            ->dropDownList($form->getRegionListOptions()); ?>

        <?= Html::submitButton(
            'Save',
            [
                'class' => 'btn btn-primary float-right mt-2'
            ]
        ); ?>

        <?= Form::end(); ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
