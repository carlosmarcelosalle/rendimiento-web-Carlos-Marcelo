

Dropzone.options.frmDropzone =
    {
        url: "/upload",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks: true,
        acceptedFiles: '.png,.jpg',
        init: function () {
            var Dropzone = this;

            $('#submit').click(function (e) {
                e.preventDefault()
                Dropzone.processQueue();
            })
            this.on('sending', function (file, xhr, formData) {
                var data = $('#frmDropzone').serializeArray();
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value)
                })
            })
        }


    }
