<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var \budyaga\cropper\Widget $widget
 *
 */

use yii\helpers\Html;

?>

<div class="cropper-widget">
    <?php echo Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo-field']); ?>
    <?php echo Html::img(
        $model->{$widget->attribute} != ''
            ? $model->{$widget->attribute}
            : $widget->noPhotoImage,
        [
            'style' => 'height: ' . $widget->height . 'px; width: ' . $widget->width . 'px',
            'class' => 'thumbnail center-block',
            'data-no-photo' => $widget->noPhotoImage
        ]
    ); ?>

    <div class="cropper-buttons hidden">
        <button type="button" class="btn btn-sm btn-danger delete-photo" aria-label="<?php echo Yii::t('cropper', 'DELETE_PHOTO');?>">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <?php echo Yii::t('cropper', 'DELETE_PHOTO');?>
        </button>
        <button type="button" class="btn btn-sm btn-success crop-photo" aria-label="<?php echo Yii::t('cropper', 'CROP_PHOTO');?>">
            <span class="glyphicon glyphicon-scissors" aria-hidden="true"></span> <?php echo Yii::t('cropper', 'CROP_PHOTO');?>
        </button>
        <button type="button" class="btn btn-sm btn-info upload-new-photo" aria-label="<?php echo Yii::t('cropper', 'UPLOAD_ANOTHER_PHOTO');?>">
            <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> <?php echo Yii::t('cropper', 'UPLOAD_ANOTHER_PHOTO');?>
        </button>
    </div>

    <div class="new-photo-area" style="height: <?php echo $widget->cropAreaHeight; ?>px; width: <?php echo $widget->cropAreaWidth; ?>px;">
        <div class="cropper-label">
            <span><?php echo $widget->label;?></span>
        </div>
    </div>
    <div class="progress hidden" style="width: <?php echo $widget->cropAreaWidth; ?>px;">
        <div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" style="width: 0%">
            <span class="sr-only"></span>
        </div>
    </div>
</div>