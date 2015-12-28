<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var \budyaga\cropper\Widget $widget
 *
 */

use yii\helpers\Html;

?>

<div class="cropper_widget">
    <p>
        <button type="button" class="btn btn-danger delete_photo" aria-label="<?= Yii::t('cropper', 'DELETE_PHOTO');?>">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <?= Yii::t('cropper', 'DELETE_PHOTO');?>
        </button>
    </p>
    <?= Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo_field']); ?>
    <?= Html::img(
        ($model->{$widget->attribute} != '') ? $model->{$widget->attribute} : $widget->noPhotoImage,
        ['style' => 'height: ' . $widget->height . 'px; width: ' . $widget->width . 'px', 'class' => 'thumbnail center-block', 'data-no-photo' => $widget->noPhotoImage]
    ); ?>

    <div class="cropper_buttons hidden">
        <button type="button" class="btn btn-success crop_photo btn-sm" aria-label="<?= Yii::t('cropper', 'CROP_PHOTO');?>">
            <span class="glyphicon glyphicon-scissors" aria-hidden="true"></span> <?= Yii::t('cropper', 'CROP_PHOTO');?>
        </button>
        <button type="button" class="btn btn-info upload_new_photo btn-sm aria-label="<?= Yii::t('cropper', 'UPLOAD_ANOTHER_PHOTO');?>">
        <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> <?= Yii::t('cropper', 'UPLOAD_ANOTHER_PHOTO');?>
        </button>
    </div>

    <div class="new_photo_area" style="height: <?= $widget->cropAreaHeight; ?>px; width: <?= $widget->cropAreaWidth; ?>px;">
        <div class="cropper_label">
            <span><?= $widget->label;?></span>
        </div>
    </div>
    <div class="progress hidden" style="width: <?= $widget->cropAreaWidth; ?>px;">
        <div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" style="width: 0%">
            <span class="sr-only"></span>
        </div>
    </div>
</div>