<?php

namespace nyatw\cropper\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 */
class CropperAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@nyatw/cropper/web/';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/cropper.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/cropper.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'nyatw\cropper\assets\JcropAsset',
        'nyatw\cropper\assets\SimpleAjaxUploaderAsset',
    ];
}
