<?php

namespace budyaga\cropper\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\web\BadRequestHttpException;
use budyaga\cropper\Widget;
use Yii;


class CropAction extends Action
{
    public $path;
    public $tmpPath;
    public $url;
    public $validatorOptions = [];
    public $width;
    public $height;

    /**
     * @inheritdoc
     */
    public function init()
    {
        Widget::registerTranslations();
        if ($this->url === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'url']));
        } else {
            $this->url = rtrim($this->url, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        if ($this->path === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'path']));
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        if ($this->tmpPath === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'tmpPath']));
        } else {
            $this->tmpPath = rtrim(Yii::getAlias($this->tmpPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Image::crop(
                $this->tmpPath . $request->post('filename'),
                $request->post('w'),
                $request->post('h'), [$request->post('x'), $request->post('y')]
            )->resize(
                new Box($this->width, $this->height)
            )->save($this->path . $request->post('filename'));

            return $this->url . $request->post('filename');
        } else {
            throw new BadRequestHttpException(Yii::t('cropper', 'ONLY_POST_REQUEST'));
        }
    }
}
