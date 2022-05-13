<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Mailery\Widget\Select\Select;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Mailery\Channel\Amazon\Ses\Form\CredentialsForm $form */
/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-channel-amazon-ses/views/default/_layout.php')
    ->parameters(compact('channel', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>

<div class="row">
    <div class="col-12">
        <?= Form::widget()
                ->csrf($csrf)
                ->id('channel-amazon-ses-credentials-form')
                ->begin(); ?>

        <?= $field->text($form, 'key')->autofocus(); ?>

        <?= $field->password($form, 'secret'); ?>

        <?= $field->select(
                $form,
                'region',
                [
                    'class' => Select::class,
                    'items()' => [$form->getRegionListOptions()],
                    'clearable()' => [false],
                ]
            ); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save changes'); ?>

        <?= Form::end(); ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
