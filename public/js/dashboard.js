$(document).ready(function() {

    /* Сохранение новой новости */
    $('#save-button').on('click', function () {
        $('form[name="news-save"]').submit();
    });

    /* Обновление контакта */
    // $('a.contact-update').on('click', function () {
    //     $('form[name="update_contact"]').submit();
    // });

});