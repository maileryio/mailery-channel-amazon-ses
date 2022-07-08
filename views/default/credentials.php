<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Mailery\Widget\Select\Select;
use Yiisoft\Html\Tag\Form;
use Yiisoft\Yii\Widgets\ContentDecorator;
use Yiisoft\Form\Field;

/** @var Mailery\Channel\Amazon\Ses\Form\CredentialsForm $form */
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
        <?= Form::tag()
                ->csrf($csrf)
                ->id('channel-amazon-ses-credentials-form')
                ->post()
                ->open(); ?>

        <?= Field::text($form, 'key')->autofocus(); ?>

        <?= Field::password($form, 'secret'); ?>

        <?= Field::input(
                Select::class,
                $form,
                'region',
                [
                    'optionsData()' => [$form->getRegionListOptions()],
                    'clearable()' => [false],
                ]
            ); ?>

        <?= Field::submitButton()
                ->content('Save changes'); ?>

        <?= Form::tag()->close(); ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
