<?php

namespace budyaga\cropper\actions;

use Imagine\Image\Point;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\imagine\BaseImage;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use budyaga\cropper\Widget;
use yii\imagine\Image;
use Imagine\Image\Box;
use Yii;

class UploadAction extends Action
{
    public $path;
    public $url;
    public $uploadParam = 'file';
    public $maxSize = 2097152;
    public $extensions = 'jpeg, jpg, png, gif';
    public $width = 200;
    public $height = 200;
    public $jpegQuality = 100;
    public $pngCompressionLevel = 1;

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
        if ($this->path === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'path']));
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
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
            $model->addRule($this->uploadParam, 'image', [
                'maxSize' => $this->maxSize,
                'tooBig' => Yii::t('cropper', 'TOO_BIG_ERROR', ['size' => $this->maxSize / (1024 * 1024)]),
                'extensions' => explode(', ', $this->extensions),
                'wrongExtension' => Yii::t('cropper', 'EXTENSION_ERROR', ['formats' => $this->extensions])
            ])->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError($this->uploadParam)
                ];
            } else {
                $model->{$this->uploadParam}->name = uniqid() . '.' . $model->{$this->uploadParam}->extension;
                $request = Yii::$app->request;

                // desired props
                $width = $request->post('width', $this->width);
                $height = $request->post('height', $this->height);

                // get image with expected proportions before crop
                $adjustedImage = $this->getAjustedImage(
                    $file->tempName,
                    $width,
                    $height
                );

                $image = $this->cropImage(
                    $adjustedImage,
                    $width,
                    $height,
                    $request->post('x'),
                    $request->post('y'),
                    $request->post('x2'),
                    $request->post('y2'),
                );

                if (!file_exists($this->path) || !is_dir($this->path)) {
                    $result = [
                        'error' => Yii::t('cropper', 'ERROR_NO_SAVE_DIR')]
                    ;
                } else {
                    $saveOptions = ['jpeg_quality' => $this->jpegQuality, 'png_compression_level' => $this->pngCompressionLevel];
                    if ($image->save($this->path . $model->{$this->uploadParam}->name, $saveOptions)) {
                        $result = [
                            'filelink' => $this->url . $model->{$this->uploadParam}->name
                        ];
                    } else {
                        $result = [
                            'error' => Yii::t('cropper', 'ERROR_CAN_NOT_UPLOAD_FILE')
                        ];
                    }
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {
            throw new BadRequestHttpException(Yii::t('cropper', 'ONLY_POST_REQUEST'));
        }
    }

    /**
     * Adjust inputed image for desired final proportions
     *
     * @param string $file_path
     * @param int $finalWidth for width calc
     * @param int $finalHeight for height calc
     *
     * @return \Imagine\Image\ImageInterface
     */
    protected function getAjustedImage(string $file_path, int $finalWidth, int $finalHeight)
    {
        $originalImage = Image::getImagine()->open($file_path);
        $sizes = $originalImage->getSize();

        // calcs props by desired final sizes
        if ($sizes->getWidth() > $sizes->getHeight()) {
            $backWidth = $sizes->getWidth();
            $backHeight = $sizes->getWidth() * ($finalWidth / $finalHeight);
        } else {
            $backWidth = $sizes->getHeight() * ($finalHeight / $finalWidth);
            $backHeight = $sizes->getHeight();
        }

        $image = Image::getImagine()
            ->create(new Box($backWidth, $backHeight)) // back layout
            ->paste( // our image pasted on top
                $originalImage->resize(
                    $originalImage->getSize()->widen($backWidth)
                ),
                new Point(
                    0, 0
                )
            );

        return $image;
    }

    /**
     * Crop inputed image
     *
     * @param \Imagine\Gd\Image $image
     * @param int $width final width
     * @param int $height final height
     * @param int $x
     * @param int $y
     * @param int $x2
     * @param int $y2
     *
     * @return \Imagine\Image\ImageInterface
     */
    protected function cropImage(\Imagine\Gd\Image $image, int $width, int $height, int $x, int $y, int $x2, int $y2)
    {
        // get sizes of imagefor props calculating
        $sizes = $image->getSize();

        // get points X and Y, and size of fileds to crop
        $propX = ceil($sizes->getWidth() / ($width / $x));
        $propY = ceil($sizes->getHeight() / ($height / $y));
        $propW = ceil(($x2 - $x) * ($sizes->getWidth() / $width));
        $propH = ceil(($y2 - $y) * ($sizes->getHeight() / $height));

        $cropedImage = Image::crop(
            $image,
            intval($propW),
            intval($propH),
            [$propX, $propY]
        )->resize(
            new Box($width, $height)
        );

        return $cropedImage;
    }
}
