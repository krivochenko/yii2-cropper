Cropper
===========
Yii-Framework extension for uploading and cropping images

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist budyaga/yii2-cropper "*"
```

or add

```
"budyaga/yii2-cropper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```
use budyaga\cropper\Widget;
```


```
<?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
	<?= $form->field($model, 'photo')->widget(Widget::className(), [
		'noPhotoImage' => '/img/nophoto.png',
		'width' => User::PHOTO_WIDTH,
		'height' => User::PHOTO_HEIGHT,
		'cropAreaWidth' => User::TMP_PHOTO_WIDTH,
		'cropAreaHeight' => User::TMP_PHOTO_HEIGHT,
		'uploadUrl' => Url::toRoute('/user/uploadPhoto'),
		'cropUrl' => Url::toRoute('/user/cropPhoto'),
	]) ?>
	<div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	</div>
<?php ActiveForm::end(); ?>
```

Add following constanses in User model:

```
const PHOTO_WIDTH = 200; //final image width
const PHOTO_HEIGHT = 200; //final image height

const TMP_PHOTO_WIDTH = 300; //image width after uploading but before cropping
const TMP_PHOTO_HEIGHT = 300; //image height after uploading but before cropping

const TMP_PHOTO_URL = '/uploads/user/tmp_photo'; //url uploaded image
const PHOTO_URL = '/uploads/user/photo'; //url finaly image

const TMP_PHOTO_PATH = '@webroot/uploads/user/tmp_photo'; //directory for uploading before cropping
const PHOTO_PATH = '@webroot/uploads/user/photo'; //directory for saving finaly image after cropping
```

In UserController:

```
public function actions()
{
	return [
		'uploadPhoto' => [
			'class' => 'budyaga\cropper\actions\UploadAction',
			'url' => User::TMP_PHOTO_URL,
			'tmpPath' => User::TMP_PHOTO_PATH,
			//options for validation uploaded image http://www.yiiframework.com/doc-2.0/yii-validators-imagevalidator.html
			'validatorOptions' => [ 
				'maxWidth' => 2000,
				'maxHeight' => 2000
			],
			'width' => User::TMP_PHOTO_WIDTH,
			'height' => User::TMP_PHOTO_HEIGHT
		],
		'cropPhoto' => [
			'class' => 'budyaga\cropper\actions\CropAction',
			'url' => User::PHOTO_URL,
			'path' => User::PHOTO_PATH,
			'tmpPath' => User::TMP_PHOTO_PATH,
			'width' => User::PHOTO_WIDTH,
            'height' => User::PHOTO_HEIGHT
		]
	];
}
```

Operates as follows:
--------------------

User click on new photo area or drag file

![g4n7fva](https://cloud.githubusercontent.com/assets/7313306/7107319/a09bb4a0-e16a-11e4-9ac5-f57509ba841b.png)

The picture is downloaded and saved in the directory TMP_PHOTO_PATH. The downloaded image is available TMP_PHOTO_URL/new_image_name

![yeul3gy](https://cloud.githubusercontent.com/assets/7313306/7107329/02f3eeba-e16b-11e4-9f9d-fb07944a91df.png)

This picture is displayed in the widget and users have the ability to crop it or upload another picture

![jaungjk](https://cloud.githubusercontent.com/assets/7313306/7107356/8581f3ae-e16b-11e4-8151-d08a4d16f1a0.png)

When the user clicks "Crop image", the server sends a request to crop the image. As a result of the request, a copy of the cropped image is saved in PHOTO_PATH. The cropped image is available at PHOTO_URL/crop_image_name. This picture is displayed in the form, and user can save it, or change crop area, or upload another photo.

![0ejh55q](https://cloud.githubusercontent.com/assets/7313306/7107359/bddeae36-e16b-11e4-889b-484d7dbad8a5.png)
