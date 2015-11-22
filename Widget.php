<?php

namespace budyaga\cropper;

use budyaga\cropper\assets\CropperAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

class Widget extends InputWidget
{
    public $uploadParameter = 'file';
    public $width = 200;
    public $height = 200;
    public $label = '';
    public $uploadUrl;
    public $noPhotoImage = '';
    public $maxSize = 2097152;
    public $cropAreaWidth = 300;
    public $cropAreaHeight = 300;
    public $extensions = 'jpeg, jpg, png, gif';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::registerTranslations();

        if ($this->uploadUrl === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'uploadUrl']));
        } else {
            $this->uploadUrl = rtrim(Yii::getAlias($this->uploadUrl), '/') . '/';
        }

        if ($this->label == '') {
            $this->label = Yii::t('cropper', 'DEFAULT_LABEL');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientAssets();

        return $this->render('widget', [
            'model' => $this->model,
            'widget' => $this
        ]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        $view = $this->getView();
        $assets = CropperAsset::register($view);

        if ($this->noPhotoImage == '') {
            $this->noPhotoImage = $assets->baseUrl . '/img/nophoto.png';
        }

        $settings = [
            'url' => $this->uploadUrl,
            'name' => $this->uploadParameter,
            'maxSize' => $this->maxSize / 1024,
            'allowedExtensions' => explode(', ', $this->extensions),
            'size_error_text' => Yii::t('cropper', 'TOO_BIG_ERROR', ['size' => $this->maxSize / (1024 * 1024)]),
            'ext_error_text' => Yii::t('cropper', 'EXTENSION_ERROR', ['formats' => $this->extensions]),
            'accept' => 'image/*'
        ];

        $view->registerJs(
            'jQuery("#' . $this->options['id'] . '").siblings(".new_photo_area").cropper(' . Json::encode($settings) . ', ' . $this->width . ', ' . $this->height . ');',
            $view::POS_READY
        );
    }

    /**
     * Register widget translations.
     */
    public static function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['cropper']) && !isset(Yii::$app->i18n->translations['cropper/*'])) {
            Yii::$app->i18n->translations['cropper'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@budyaga/cropper/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'cropper' => 'cropper.php'
                ]
            ];
        }
    }
}
