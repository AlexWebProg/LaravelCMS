// Добавление параметра в список
$('#add_subscribe_parameter_button').click(function(){
    $('#subscribe_parameter_rows').append($('#new_subscribe_parameter_block').html());
    $('#subscribe_parameter_rows input[type="text"][name="value[]"]:last').focus();
    showFormActions();
});

// Выключение активности значения: используется ли значение в подписках
$('#main_form')
    .on('change','input[type="checkbox"][name="is_active[]"]',function(){
    let element = $(this),
        id = parseInt(element.closest('.row').data('element_id'),10);
    if (!element.is('checked') && id > 0) {
        // Проверяем, используется ли значение в подписках
        // Если да, включаем активность обратно
        $.post('checkSubscribeSettingUse',
            {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id,
            })
            .done(function(result) {
                if (parseInt(result,10) === 1) {
                    element.prop('checked',true);
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        icon: 'fa fa-exclamation-triangle fa-lg',
                        title: 'Значение используется',
                        body: 'Это значение нельзя отключить или удалить, так как оно используется в подписках'
                    });
                }

            })
            .fail(function() {
                element.prop('checked',true);
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    icon: 'fa fa-exclamation-triangle fa-lg',
                    title: 'Ошибка',
                    body: 'Кажется, что-то пошло не так. Попробуйте обновить страницу'
                })
            });
    }
})
    // Удаление значения: используется ли значение в подписках
    .on('click','.delete_subscribe_parameter_button',function(){
    let row = $(this).closest('.row'),
        id = parseInt(row.data('element_id'),10);
    if (id > 0) {
        // Подтверждение
        $.alert({
            type: 'blue',
            title: 'Подтверждение',
            content: 'Действительно удалить?<br/><hr/>',
            closeIcon: true,
            backgroundDismiss: true,
            buttons: {
                yes: {
                    text: 'Удалить',
                    btnClass: 'btn-primary',
                    action: function(){
                        // Проверяем, используется ли значение в подписках
                        // Если нет, удаляем
                        $.post('deleteSubscribeSetting',
                            {
                                '_token': $('meta[name=csrf-token]').attr('content'),
                                id: id,
                            })
                            .done(function(result) {
                                if (parseInt(result,10) === 0) {
                                    $(document).Toasts('create', {
                                        class: 'bg-danger',
                                        icon: 'fa fa-exclamation-triangle fa-lg',
                                        title: 'Значение используется',
                                        body: 'Это значение нельзя отключить или удалить, так как оно используется в подписках'
                                    });
                                } else {
                                    row.remove();
                                }
                            })
                            .fail(function() {
                                $(document).Toasts('create', {
                                    class: 'bg-danger',
                                    icon: 'fa fa-exclamation-triangle fa-lg',
                                    title: 'Ошибка',
                                    body: 'Кажется, что-то пошло не так. Попробуйте обновить страницу'
                                })
                            });
                    }
                },
                no: {
                    text: 'Нет',
                    btnClass: 'btn-default'
                }
            }
        });
    } else {
        // Это только что добавленная строка, ещё не сохранённая в БД
        // Просто убираем её
        row.remove();
    }
});