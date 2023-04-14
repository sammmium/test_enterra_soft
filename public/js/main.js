$(document).ready(function() {
    /* Меню */
    $('div.menu-item').each(function (index, item) {
        item.classList.remove('active');
    });
    $('div.menu-item').each(function (index, item) {
        let selected_menu_item = $('input[name="selected_menu_item"]').val();
        if (item.getAttribute('data-alias') === selected_menu_item) {
            item.classList.add('active');
        }
    });
});