<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 22.11.2015
 * Time: 19:20
 */

namespace nyatw\cropper\assets;


use yii\web\AssetBundle;

class SimpleAjaxUploaderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/simple-ajax-uploader/';

    public $js = [
        'SimpleAjaxUploader.min.js'
    ];
}
