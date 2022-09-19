// Подтверждение удаления пользователя
$('i.confirm_user_delete').click(function(evt){
    evt.preventDefault();
    let clicked_item = $(this),
        user_name = clicked_item.data('user_name'),
        currentForm = clicked_item.parent();
    $.alert({
        type: 'blue',
        title: 'Удаление пользователя',
        content: 'Удалить пользователя <span style="color:#b00000;">'+user_name+'</span>?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    currentForm.submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});

// Текстовый редактор summenrnote
$(document).ready(function () {
    $('.summernote').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
});

// Загрузка файла
$(function () {
    bsCustomFileInput.init();
});

// Select2
$('.select2').select2();

// Таблица заявок по подпискам
$(function () {
    $("#subRequestsTable").DataTable({
        "lengthChange": false,
        "autoWidth": false,
        "paging": false,
        "ordering": true,
        "info": true,
        "searching": true,
        fixedHeader: true,
        "language": {
            "url": "/plugins/datatables_rus/ru.json"
        },
        order: [[5, 'desc'],[4, 'desc']],
        "responsive": true,
        columnDefs: [
            { responsivePriority: 1, targets: -1 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: 3 },
            { orderable: false, targets: -1 }
        ]
    });
});

// Автоматическое скрытие оповещений об успехе
$(document).ready(function () {
    setTimeout(function(){
        $('.toast.bg-success').toast('hide')
    },2000);
});

// Действия кнопок сохранения формы
$('#form_actions button.btn.btn-danger').click(function(){
    location.reload();
});
$('#form_actions button.btn.btn-success').click(function(){
    $('#main_form_submit').click();
});

// Отображение кнопок сохранения формы
function showFormActions(){
    $('#form_actions').css('bottom','0');
}

// Отображение кнопок сохранения формы при изменении поля ввода
$('#main_form input, #main_form select, #main_form textarea').bind('change, input', function(){
    showFormActions();
});
// Отключаем срабатывание на datetimepicker
$('#main_form input.datetimepicker-input')
    .unbind('input')
    .bind('change change.datetimepicker', function(){
        if ($(this).val() !== this.defaultValue) {
            showFormActions();
        }
    });

// Убираем класс ошибки валидации при изменении поля ввода
$('.is-invalid').bind('keyup change change.datetimepicker', function(){
    $(this).removeClass('is-invalid');
    $('#' + $(this).attr('id') + '_error').remove();
});

// JQuery Sortable: перетягиваемые вверх-вниз строки для изменения сортировки
$(function() {
    $(".sortable_rows").sortable({
        axis: "y",
        containment: ".sortable_rows",
        cursor: "ns-resize",
        handle: ".sortable_handle",
        items: "> div.row",
        scroll: true,
        tolerance: "pointer",
        classes: {
            "ui-sortable-helper": "sorting_now"
        },
        change: function( event, ui ) {showFormActions();}
    }).disableSelection();
});

// Если есть сообщения об ошибках формы, показываем её кнопки сохранения
$().ready(function(){
    if ($('#validation_errors_notification').length && $('#main_form').length) {
        showFormActions();
    }
});
