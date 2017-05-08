<?php

namespace budyaga\cropper\assets;

use yii\web\AssetBundle;

class JcropAsset extends AssetBundle
{
    public $sourcePath = '@bower/jcrop/';

    public $js = [
        'js/jquery.Jcrop.min.js'
    ];

    public $css = [
        'css/jquery.Jcrop.min.css'
    ];
}