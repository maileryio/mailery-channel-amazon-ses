<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Form\Widget\Form;

/** @var Mailery\Channel\Amazon\Ses\Form\ChannelForm $form */
/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
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
                ->id('channel-credentials-form')
                ->begin(); ?>

        <?= $field->text($form, 'key')->autofocus(); ?>

        <?= $field->password($form, 'secret'); ?>

        <?= $field->select($form, 'region', ['items()' => [$form->getRegionListOptions()]]); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save changes'); ?>

        <?= Form::end(); ?>
    </div>
</div>
