$(document).ready(function() {

    /* Сохранение новости */
    $('#save-button').on('click', function () {
        $('form[name="news-save"]').submit();
    });

});