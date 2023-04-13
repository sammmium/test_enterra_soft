$(document).ready(function() {
    $('select[name="period"]').on('change', function () {
        let el = $(this);
        $('input[name="selected_period"]').val(el.val());
    });
    $('select[name="contact"]').on('change', function () {
        let el = $(this);
        $('input[name="selected_contact"]').val(el.val());
    });
    if ($('select[name="period"]').val() == '0' && $('select[name="contact"]').val() == '0') {
        $('a.filter-button').hide();
    }
    $('select[name="period"]').on('change', function () {
        if ($(this).val() !== '0') {
            $('a.filter-button').show();
        }
        if ($(this).val() == '0' && $('select[name="contact"]').val() == '0') {
            $('a.filter-button').hide();
        }
    });
    $('select[name="contact"]').on('change', function () {
        if ($(this).val() !== '0') {
            $('a.filter-button').show();
        }
        if ($(this).val() == '0' && $('select[name="period"]').val() == '0') {
            $('a.filter-button').hide();
        }
    });
});
