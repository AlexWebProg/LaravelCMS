let content_wrapper = $('.content-wrapper');
// Select2
$('select.input-filter-subscribeConsistTable').select2({
    multiple: true,
    closeOnSelect: true,
    dropdownAutoWidth: true
}).select2('open').select2('close');

// Форматируем вводимые в фильтр данные
function strip_tags(html){
    return html.replace(/<.*?>/g, '');
}

// Подсветка выбранных фильтров в шапке таблицы
function colorFilters() {
    $('table.table.table-sс thead > tr.filters > th > input.input-filter-subscribeConsistTable').each(function(){
        $(this).removeClass('filter-applied');
        if ($(this).val() !== '') $(this).addClass('filter-applied');
    });
}

// Сортирует данные столбца таблицы по data-order, затем по алфавиту
function sortObj(obj) {
    return Object.entries(obj)
        .sort((a, b) =>
            a[1] !== b[1] ?
                a[1] - b[1] :
                b[0] < a[0] ? 1 : -1
        )
        .map(e => e[0]);
}

// Получает значение select2-фильтра
function getSelect2Value(element){
    let data = $.map( element.select2('data'), function( value, key ) {
        return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
    });
    if (data.length === 0) {
        data = [""];
    }
    return data.join('|');
}

// Заполняем значения select-ов фильтров
function completeSelectOptions(api){
    api
        .columns()
        .eq(0)
        .each(function (colIdx) {
            let inputSelect = $('select', $('.filters th').eq($(api.column(colIdx).header()).index()));
            if (inputSelect.length === 1) {
                let searchValues = api.column(colIdx).search().split("|");
                inputSelect.find("option").remove();
                let arColumnData = {};
                api.column(colIdx, {search: 'none'}).nodes().toArray().map(function(node) {
                    if ($(node).data('filter')) {
                        arColumnData[$(node).data('filter')] = $(node).attr('data-order');
                    } else {
                        arColumnData[strip_tags($(node).text())] = $(node).attr('data-order');
                    }
                });
                sortObj(arColumnData).map(function (optionValue) {
                    let searchCompare = '^' + $.fn.dataTable.util.escapeRegex(optionValue) + '$';
                    if (optionValue !== '') {
                        if (searchValues.indexOf(searchCompare) === -1) {
                            inputSelect.append('<option value="' + optionValue + '">' + optionValue + '</option>');
                        } else {
                            inputSelect.append('<option value="' + optionValue + '" selected="selected">' + optionValue + '</option>');
                        }
                    }
                });
            }
        });
}

// Указывает значения select-ов для фильтра по-умолчанию
function setDefaultFilter(){
    $('.plannedSendMonthSelect').each(function(){
        let arValues = [];
        $(this).find("option").each(function(){
            let value = $(this).text();
            if (value !== '-' && value !== '+') {
                arValues.push('^' + value + '$');
            }
        });
        if (arValues.length > 0) {
            $(this).attr('data-default',arValues.join('|'));
            return false;
        }
    });
}

// Обновляет selectы: делает недоступные options скрытыми
function updateSelectOptions(api) {
    api
        .columns()
        .eq(0)
        .each(function (colIdx) {
            let inputSelect = $('select', $('.filters th').eq($(api.column(colIdx).header()).index()));
            if (inputSelect.length === 1) {
                // Если у selectа есть отмеченные значения, пересчитываем таблицу с неотимеченным этим
                // selectом, чтобы он не ограничивал сам себя
                let val = getSelect2Value(inputSelect);
                if (val) {
                    api
                        .column(colIdx)
                        .search('', true, false);
                }
                let arColumnData = {};
                api.column(colIdx, {search: 'applied'}).nodes().toArray().map(function (node) {
                    if ($(node).data('filter')) {
                        arColumnData[$(node).data('filter')] = true;
                    } else {
                        arColumnData[strip_tags($(node).text())] = true;
                    }
                });
                inputSelect.find("option").each(function () {
                    if (arColumnData[$(this).text()]) {
                        $(this).prop('disabled', false);
                    } else {
                        $(this).prop('disabled', true);
                    }
                });
                if (val) {
                    api
                        .column(colIdx)
                        .search(val, true, false);
                }
            }
        });
}

$(document).ready(function () {
    let subscribeConsistTable = $("#subscribeConsistTable").DataTable({
        ajax: 'indexJSON/'+$("#subscribeConsistTable").data('month'),
        processing: true,
        bAutoWidth: false,
        columns: [
            { data: 'checkbox' },
            {
                data: {
                    _: 'type.display',
                    sort: 'type.order',
                },
            },
            {
                data: {
                    _: 'added.display',
                    sort: 'added.order',
                },
            },
            { data: 'subscribe_cost_str' },
            { data: 'subscriber' },
            {
                data: {
                    _: 'pref_acc.display',
                    filter: 'pref_acc.filter',
                },
            },
            { data: 'sizes' },
            { data: 'consist' },
            { data: 'send_info' },
            { data: 'edit_btn' },
        ],
        createdRow: function( row, data ) {
            $( row ).find('td:eq(1)').attr('data-order', data.type.order);
            $( row ).find('td:eq(2)').attr('data-order', data.added.order);
            $( row ).find('td:eq(5)').attr('data-filter', data.pref_acc.filter);
        },
        stateSave: true,
        paging: false,
        info: true,
        searching: true,
        responsive: false,
        fixedHeader: true,
        columnDefs: [
            {
                orderable: false,
                searchable: false,
                targets: [0, -1]
            }
        ],
        orderCellsTop: true,
        order: [[ 2, "desc" ]],
        language: { // language settings
            // data tables spesific
            "lengthMenu": "По _MENU_ записей",
            "search": "Поиск: ",
            "info": "Найдено: _TOTAL_ ",
            "infoEmpty": "К сожалению, ничего не найдено.",
            "emptyTable": "Данные недоступны",
            "zeroRecords": "Совпадений не найдено.",
            "sInfoFiltered": "(из _MAX_)",
            "paginate": {
                "previous": "Пред.",
                "next": "След.",
                "last": "Последний",
                "first": "Первый",
                "page": "Страница",
                "pageOf": "из"
            }
        },
        initComplete: function () {
            let api = this.api();
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // При нажатии input search
                    $('input[type="search"]',$('.filters th').eq($(api.column(colIdx).header()).index()))
                        .on('keyup change clear search', function (e) {
                            e.stopPropagation();
                            if (this.value !== $(this).data('prev')) {
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(this.value)
                                    .draw();
                                $(this).focus().data('prev',this.value);
                                updateSelectOptions(api);
                                colorFilters();
                                toggleGroupActionButtons();
                            }
                        });
                    // При выборе select
                    $('select',$('.filters th').eq($(api.column(colIdx).header()).index()))
                        .on('change', function (e) {
                            let val = getSelect2Value($(this));
                            api
                                .column(colIdx)
                                .search( val ? val : '', true, false )
                                .draw();
                            updateSelectOptions(api);
                            colorFilters();
                            toggleGroupActionButtons();
                        });
                });
            completeSelectOptions(api);
            setDefaultFilter();
            // Если есть сохранённые фильтры, пересчитываем select-ы
            let state = api.state.loaded();
            if (state) {
                updateSelectOptions(api);
                setTimeout(function(){api.fixedHeader.adjust();},200);
            }
        },
        stateLoaded: function stateLoadedCallback(settings, state) {
            state.columns.forEach(function (column, index) {
                if (index > 0 && column.search.search !== 'NaN') {
                    $('#subscribeConsistTable > thead > tr.filters > th:eq('+index+') > input[type="search"]').val(column.search.search).data('prev',column.search.search);
                }
            });
            colorFilters();
        }
    });

    // Фильтр по-умолчанию
    $('.filter_default').click(function(){
        subscribeConsistTable
            .columns()
            .eq(0)
            .each(function (colIdx) {
                let val = '';
                let inputSelect = $('select', $('.filters th').eq($(subscribeConsistTable.column(colIdx).header()).index()));
                if (inputSelect.length === 1) {
                    if (inputSelect.data('default')) {
                        val = inputSelect.data('default');
                    }
                }
                subscribeConsistTable
                    .column(colIdx)
                    .search(val, true, false);
            });
        subscribeConsistTable
            .order([2, 'desc'])
            .search('')
            .draw();
        window.location.reload();
    });

    // Сброс фильтров
    $('.filter_cancel').click(function(){
        subscribeConsistTable.state.clear();
        window.location.reload();
    });

    let group_action_buttons = $('#group_action_buttons');
    $('#subscribeConsistTable_filter')
        .closest('.row')
        .find('.col-sm-12.col-md-6:first-child')
        .html(group_action_buttons.html());
    group_action_buttons.remove();

    $(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
        setTimeout(function () {
            subscribeConsistTable.fixedHeader.adjust();
        }, 500);
    });

});

function toggleGroupActionButtons(){
    if ($('.subscribe_checkbox:checked').length) {
        $('.group_action_button').prop('disabled',false);
        $('#main_checkbox').prop('checked',true);
    } else {
        $('.group_action_button').prop('disabled',true);
        $('#main_checkbox').prop('checked',false);
    }
}

// Клик главного чекбокса
$('#main_checkbox').click(function(){
    let state = $(this).is(':checked');
    $('.subscribe_checkbox').each(function(){
        $(this).prop('checked',state);
        toggleActiveRow($(this));
    });
    if (state) {
        $('.group_action_button').prop('disabled',false);
    } else {
        $('.group_action_button').prop('disabled',true);
    }
});

// Клик чекбокса подписки
content_wrapper.on('click','.subscribe_checkbox',function(){
    toggleActiveRow($(this));
    toggleGroupActionButtons();
});

// Отмечаем строку подписки выбранной или нет
function toggleActiveRow(checkbox){
    if (checkbox.is(':checked')) {
        checkbox.closest('tr').addClass('active');
    } else {
        checkbox.closest('tr').removeClass('active');
    }
}

// Отправка подписок: форма отправки
content_wrapper.on('click','#showSendFormBtn',function(){
    $('#sendFormModal').modal('show');
    let form = $('#sendForm');
    form.find('.sendForm_subscribe_id').remove();
    $.each($('input.subscribe_checkbox:checked'), function(){
        form.append('<input type="hidden" class="sendForm_subscribe_id" name="subscribe_id[]" value="' + $(this).val() + '" />');
    });
});
$().ready(function () {
    // Дата отправки
    $('#sending_date').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY HH:mm',
        sideBySide: true
    });
    // Сворачиваем меню слева
    $('[data-widget="pushmenu"]').PushMenu('collapse');
});

// Отправка подписок
$(function() {
    $("#sendForm").on("submit", function() {
        $("#sendForm .text-danger").remove();
        $("#sendForm .is-invalid").removeClass('is-invalid');
        $.post($(this).attr("action"), $(this).serialize(), function(data) {
            window.location.reload();
        }).fail(function(response) {
            let erroJson = response.responseJSON.errors,
                arErrors = [];
            for (let err in erroJson) {
                for (let errstr of erroJson[err]) {
                    let input = $("#sendForm [name='" + err + "']");
                    if (input.length > 0) {
                        input.addClass('is-invalid').after("<div id='" + err + "_error' class='text-danger'>" + errstr + "</div>");
                    } else {
                        arErrors.push(errstr);
                    }
                }
            }
            if (arErrors.length > 0) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    icon: 'fa fa-exclamation-triangle fa-lg',
                    title: 'Ошибка',
                    body: arErrors.join('<br/>')
                });
            }
        });
        return false;
    });
});

// Экспорт в PDF
content_wrapper.on('click','#PDFExportBtn',function(){
    let form = $('#PDFExportForm');
    form.find('.PDFExportForm_subscribe_id').remove();
    $.each($('input.subscribe_checkbox:checked'), function(){
        form.append('<input type="hidden" class="PDFExportForm_subscribe_id" name="subscribe_id[]" value="' + $(this).val() + '" />');
    });
    if (form.find('.PDFExportForm_subscribe_id').length > 0) {
        form.submit();
    } else {
        $(document).Toasts('create', {
            class: 'bg-danger',
            icon: 'fa fa-exclamation-triangle fa-lg',
            title: 'Ошибка',
            body: 'Похоже, не выбрана ни одна подписка'
        });
    }
});
