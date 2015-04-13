(function($) {
	$.fn.uploader = function(options, width, height) {
        var $cropper = this.parents('.cropper_widget');
        var $area = $cropper.find('.new_photo_area');
        var buttons = [
            $area.find('.cropper_label'),
            $cropper.find('.upload_new_photo')
        ];
		var settings = $.extend({
                button: buttons,
                dropzone: $area.find('.cropper_label'),
                responseType: "json",
                noParams: true,
                multipart: true,
                encodeCustomHeaders: false,
				onComplete: function(filename, response) {
                    if (response.error) {
                        $cropper.parents('.form-group').addClass('has-error').find('.help-block-error').text(response.error);
                        return;
                    }
                    $cropper.parents('.form-group').removeClass('has-error').find('.help-block-error').text('');
                    $cropper.find('.cropper_buttons').removeClass('hidden');
                    $area.find('.cropper_label').addClass('hidden');

                    var $existsImg = $area.find('img');
                    if ($existsImg.length) {
                        $existsImg.data('Jcrop').destroy();
                        $existsImg.remove();
                    }

                    $area.append('<img src="' + response.filelink + '" data-filename="' + response.filename + '">');

                    var x1 = (response.width - width) / 2;
                    var y1 = (response.height - height) / 2;
                    var x2 = x1 + width;
                    var y2 = y1 + height;

                    $area.find('img').Jcrop({
                        aspectRatio: width / height,
                        setSelect: [x1, y1, x2, y2],
                        boxWidth: $area.width(),
                        boxHeight: $area.height()
                    });
                }
			}, options);
		
        new ss.SimpleUpload(settings);
	};

    $('.cropper_widget').on('click', '.delete_photo', function() {
        var $cropper = $(this).parents('.cropper_widget');
        var $thumbnail = $cropper.find('.thumbnail');
        $cropper.find('.photo_field').val('');
        $thumbnail.attr({'src' : $thumbnail.data('no-photo')});
    }).on('click', '.crop_photo', function() {
        var $cropper = $(this).parents('.cropper_widget');
        var $img = $cropper.find('.new_photo_area img');
        var data = $img.data('Jcrop').tellSelect();
        var url = $(this).data('crop-url');
        data[yii.getCsrfParam()] = yii.getCsrfToken();
        data['filename'] = $img.data('filename');
        $.post(url, data, function(response) {
            $cropper.find('.thumbnail').attr({'src' : ''}).attr({'src' : response + '?' + Math.random()});
            $cropper.find('.photo_field').val(response);
        });
    });
})(jQuery);