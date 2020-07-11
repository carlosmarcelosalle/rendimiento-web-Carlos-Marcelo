$(document).ready(function () {
    $('#tagsSearch').keyup(function () {

        var value = $('#tagsSearch').val();

        $.get('/searchSubmit', { 'value': value }, function (data) {
            var $_container = $('#container');
            $_container.empty();

            if (data.length != 0) {
                data.forEach(function (element) {

                    var image = '<img src="'+ element._source.path + element._source.title + ' "/>';
                    $_container.prepend(image);
                })
            }

        })
    })
})
