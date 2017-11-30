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
    <?php echo $form->field($model, 'photo')->widget(Widget::className(), [
        'uploadUrl' => Url::toRoute('/user/user/uploadPhoto'),
    ]) ?>
    <div class="form-group">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
```
Widget has following properties:

| Name     | Description    | Default |  Required   |
| --------|---------|-------|------|
| uploadParameter  | Upload parameter name | file    |No |
| width  | The final width of the image after cropping | 200    |No |
| height  | The final height of the image after cropping | 200    |No |
| label  | Hint in box for preview | It depends on application language. You can translate this message on your language and make pull-request.    |No |
| uploadUrl  | URL for uploading and cropping image |     |Yes |
| noPhotoImage  | The picture, which is used when a photo is not loaded. | You can see it on screenshots in this instructions   |No |
| maxSize  | The maximum file size (kb).  | 2097152    |No |
| cropAreaWidth  | Width box for preview | 300    |No |
| cropAreaHeight  | Height box for preview | 300    |No |
| aspectRatio | Fix aspect ratio of cropping area | null |No |
| extensions  | Allowed file extensions (string). | jpeg, jpg, png, gif    |No |


In UserController:

```
public function actions()
{
    return [
        'uploadPhoto' => [
            'class' => 'budyaga\cropper\actions\UploadAction',
            'url' => 'http://your_domain.com/uploads/user/photo',
            'path' => '@frontend/web/uploads/user/photo',
        ]
    ];
}
```
Action has following parameters:

| Name     | Description    | Default |  Required   |
| --------|---------|-------|------|
| path  | Path for saving image after cripping |     |Yes |
| url  | URL to which the downloaded images will be available. |  |Yes |
| uploadParameter  | Upload parameter name. It must match the value of a similar parameter of the widget. | file    |No |
| maxSize  | The maximum file size (kb). It must match the value of a similar parameter of the widget. | 2097152    |No |
| extensions  | Allowed file extensions (string). It must match the value of a similar parameter of the widget. | jpeg, jpg, png, gif    |No |
| width  | The final width of the image after cropping. It must match the value of a similar parameter of the widget. | 200    |No |
| height  | The final height of the image after cropping. It must match the value of a similar parameter of the widget. | 200    |No |
| jpegQuality  | Quality of cropped image (JPG) | 100    |No |
| pngCompressionLevel  | Quality of cropped image (PNG) | 1    |No |


You can use this widget on frontend and backend. For example: user can change his userpic and administrator can change users userpic.

Operates as follows:
--------------------

User click on new photo area or drag file

![g4n7fva](https://cloud.githubusercontent.com/assets/7313306/7107319/a09bb4a0-e16a-11e4-9ac5-f57509ba841b.png)

The picture is loaded by JavaScript FileAPI.

![yeul3gy](https://cloud.githubusercontent.com/assets/7313306/7107329/02f3eeba-e16b-11e4-9f9d-fb07944a91df.png)

This picture is displayed in the widget and users have the ability to crop it or upload another picture

![jaungjk](https://cloud.githubusercontent.com/assets/7313306/7107356/8581f3ae-e16b-11e4-8151-d08a4d16f1a0.png)

When the user clicks "Crop image", a request with file and coordinates is sent to the server. This picture is displayed in the form, and user can save it, or change crop area, or upload another photo.

![0ejh55q](https://cloud.githubusercontent.com/assets/7313306/7107359/bddeae36-e16b-11e4-889b-484d7dbad8a5.png)
