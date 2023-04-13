
$(document).ready(function() {
    /* Всегда видимый календарь на странице расписаний */
    $("#datepicker").datepicker({
        onSelect: function(date){
            $('#datepicker_value').val(date);
            $('form[name="schedule_calendar"]').submit();
        }
    });
    $("#datepicker").datepicker("setDate", $('#datepicker_value').val());

    /* Форма добавления новой записи в расписание */
    $('a.add-day-schedule-item').on('click', function () {
        $('form[name="lesson_add"]').submit();
    });

    /* Применение маски к полю ввода времени */
    $('input[name="set_time"]').mask('99:99', {placeholder: "ЧЧ:ММ"});

    /* Сохранение новой записи в расписание */
    $('a.save-day-schedule-item').on('click', function () {
        let time = $('input[name="set_time"]').val();
        if (time !== '') {
            $('input[name="contact_id"]').val($('select[name="set_contact"]').val());
            $('input[name="subject_time"]').val(time);
            $('form[name="lesson_create"]').submit();
        } else {
            alert('Не указано время');
        }
    });
});