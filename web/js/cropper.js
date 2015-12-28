(function ($) {
    $.fn.cropper = function (options, width, height) {
        var cropper = {
            $widget: $(this).parent('.cropper_widget'),
            $progress: $(this).parent('.cropper_widget').find('.progress'),
            $thumbnail: $(this).find('.thumbnail'),
            uploader: null,
            reader: null,
            selectedFile: null,
            init: function() {
                cropper.reader = new FileReader();
                cropper.reader.onload = function(e) {
                    cropper.clearOldImg();

                    cropper.$widget.find('.new_photo_area').append('<img src="' + e.target.result + '">');
                    cropper.$img = cropper.$widget.find('.new_photo_area img');

                    var x1 = (cropper.$img.width() - width) / 2;
                    var y1 = (cropper.$img.height() - height) / 2;
                    var x2 = x1 + width;
                    var y2 = y1 + height;

                    cropper.$img.Jcrop({
                        aspectRatio: width / height,
                        setSelect: [x1, y1, x2, y2],
                        boxWidth: cropper.$widget.find('.new_photo_area').width(),
                        boxHeight: cropper.$widget.find('.new_photo_area').height()
                    });

                    cropper.setProgress(0);
                };

                var settings = $.extend({
                    button: [
                        cropper.$widget.find('.cropper_label'),
                        cropper.$widget.find('.upload_new_photo')
                    ],
                    dropzone: cropper.$widget.find('.cropper_label'),
                    responseType: 'json',
                    noParams: true,
                    multipart: true,
                    onChange: function() {
                        if (cropper.selectedFile) {
                            cropper.selectedFile = null;
                            cropper.uploader._queue = [];
                        }
                        return true;
                    },
                    onSubmit: function() {
                        if (cropper.selectedFile) {
                            return true;
                        }
                        cropper.selectedFile = cropper.uploader._queue[0];

                        cropper.setProgress(55);
                        cropper.showError('');
                        cropper.reader.readAsDataURL(this._queue[0].file);
                        return false;
                    },
                    onComplete: function(filename, response) {
                        cropper.$progress.addClass('hidden');
                        if (response['error']) {
                            cropper.showError(response['error']);
                            return;
                        }
                        cropper.showError('');

                        cropper.$widget.find('.thumbnail').attr({'src': response['filelink']});
                        cropper.$widget.find('.photo_field').val(response['filelink']);
                    },
                    onSizeError: function () {
                        cropper.showError(options['size_error_text']);
                        cropper.cropper.setProgress(0);
                    },
                    onExtError: function () {
                        cropper.showError(options['ext_error_text']);
                        cropper.setProgress(0);
                    }
                }, options);

                cropper.uploader = new ss.SimpleUpload(settings);

                cropper.$widget.on('click', '.delete_photo', function() {
                    cropper.deletePhoto();
                }).on('click', '.crop_photo', function() {
                    var data = cropper.$img.data('Jcrop').tellSelect();
                    data[yii.getCsrfParam()] = yii.getCsrfToken();

                    if (cropper.uploader._queue.length) {
                        cropper.selectedFile = cropper.uploader._queue[0];
                    } else {
                        cropper.uploader._queue[0] = cropper.selectedFile;
                    }
                    cropper.uploader.setData(data);

                    cropper.setProgress(1);
                    cropper.uploader.setProgressBar(cropper.$progress.find('.progress-bar'));

                    cropper.readyForSubmit = true;
                    cropper.uploader.submit();
                });
            },
            showError: function(error) {
                if (error == '') {
                    cropper.$widget.parents('.form-group').removeClass('has-error').find('.help-block').text('');
                } else {
                    cropper.$widget.parents('.form-group').addClass('has-error').find('.help-block').text(error);
                }
            },
            setProgress: function(value) {
                if (value) {
                    cropper.$widget.find('.cropper_buttons').removeClass('hidden');
                    cropper.$widget.find('.cropper_label').addClass('hidden');
                    cropper.$progress.removeClass('hidden').find('.progress-bar').css({'width': value + '%'});
                } else {
                    cropper.$progress.addClass('hidden').find('.progress-bar').css({'width': 0});
                }
            },
            deletePhoto: function() {
                cropper.$widget.find('.photo_field').val('');
                cropper.$thumbnail.attr({'src': cropper.$thumbnail.data('no-photo')});
            },
            clearOldImg: function() {
                if (cropper.$img) {
                    cropper.$img.data('Jcrop').destroy();
                    cropper.$img.remove();
                    cropper.$img = null;
                }
            }
        };

        cropper.init();
    };
})(jQuery);