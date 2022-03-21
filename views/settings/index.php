<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Brand\Entity\Brand $brand */
/** @var \Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */
?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-brand/views/settings/_layout.php')
    ->begin(); ?>

<div class="mb-5"></div>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= FlashMessage::widget(); ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-4">
        <?= Form::widget()
                ->action($urlGenerator->generate('/brand/settings/aws'))
                ->csrf($csrf)
                ->id('channel-email-amazon-ses-form')
                ->begin(); ?>

        <h3 class="h6">Amazon Web Services Credentials</h3>
        <div class="mb-4"></div>

        <?= $field->text($form, 'key')->autofocus(); ?>

        <?= $field->password($form, 'secret'); ?>

        <div class="mb-5"></div>
        <h3 class="h6">Amazon SES Region</h3>
        <div class="mb-4"></div>

        <?= $field->select($form, 'region', ['items()' => [$form->getRegionListOptions()]]); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save'); ?>

        <?= Form::end(); ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
