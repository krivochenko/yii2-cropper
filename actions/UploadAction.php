<?php

namespace budyaga\cropper\actions;

use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use budyaga\cropper\Widget;
use yii\imagine\Image;
use Yii;


class UploadAction extends Action
{
    public $tmpPath;
    public $url;
    public $uploadParam = 'file';
    public $validatorOptions = [];

    public $width = 300;
    public $height = 300;

    /**
     * @inheritdoc
     */
    public function init()
    {
        Widget::registerTranslations();
        if ($this->url === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'url']));
        } else {
            $this->url = rtrim($this->url, '/') . '/';
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
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($this->uploadParam);
            $model = new DynamicModel(compact($this->uploadParam));
            $model->addRule($this->uploadParam, 'image', $this->validatorOptions)->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError($this->uploadParam)
                ];
            } else {
                $model->{$this->uploadParam}->name = uniqid() . '.' . $model->{$this->uploadParam}->extension;

                $image = Image::getImagine()->open($file->tempName);

                if ($image->save($this->tmpPath . $model->{$this->uploadParam}->name)) {
                    $result = [
                        'filelink' => $this->url . $model->{$this->uploadParam}->name,
                        'filename' => $model->{$this->uploadParam}->name,
                        'width' => $image->getSize()->getWidth(),
                        'height' => $image->getSize()->getHeight()
                    ];
                } else {
                    $result = [
                        'error' => Yii::t('cropper', 'ERROR_CAN_NOT_UPLOAD_FILE')]
                    ;
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {
            throw new BadRequestHttpException(Yii::t('cropper', 'ONLY_POST_REQUEST'));
        }
    }
}
