// Основная форма подписки - НАЧАЛО
$().ready(function () {
    // Дата подписки
    $('#subscribe_date').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY HH:mm',
        sideBySide: true
    });
    // Автоматическая сумма подписки
    if (parseInt($('#sum_calc_type').val(),10) === 0) {
        $('#sum').prop('readonly',true);
    }
    // Следующая отправка (месяц и год)
    $('#next_send_month').datetimepicker({
        locale: 'ru',
        format: 'MMMM YYYY'
    });
});

// Формат суммы
$('#sum').inputmask({ alias : "currency", prefix: '', groupSeparator: ' ' });

// Автоматически пересчитываем сумму подписки
$('#sum_calc_type, #subscribe_type_id, #delivery_type_id, #pref_acc_id').change(function(){
    if (parseInt($('#sum_calc_type').val(),10) === 0) {
        let sum = parseInt($('#subscribe_type_id').find(':selected').data('cost'),10)
            + parseInt($('#delivery_type_id').find(':selected').data('cost'),10)
            + parseInt($('#pref_acc_id').find(':selected').data('cost'),10);
        $('#sum').val(sum).prop('readonly',true);

    }
});
$('#sum_calc_type').change(function(){
    if (parseInt($('#sum_calc_type').val(),10) === 1) {
        let sumInput = $('#sum');
        sumInput.val(sumInput.data('init-value')).prop('readonly',false).focus();
    }
});
// Основная форма подписки - КОНЕЦ

// Отправка - НАЧАЛО
$().ready(function () {
    // Дата отправки
    $('#sending_date').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY HH:mm',
        sideBySide: true
    });
    // Месяц, за который выполняется отправка
    $('#sent_month').datetimepicker({
        locale: 'ru',
        format: 'MMMM YYYY'
    });
});
// Отправка - КОНЕЦ
