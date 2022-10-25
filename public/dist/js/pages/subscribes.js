let content_wrapper = $('.content-wrapper'),
    initComplete = false;
// Select2
$('select.input-filter-subscribesTable').select2({
    multiple: true,
    closeOnSelect: true,
    dropdownAutoWidth: true
}).select2('open').select2('close');

// Горизонтальная прокрутка таблицы
$().ready(function () {
    setTimeout(function () {
        $(".dataTables_scrollBody").floatingScroll("init")
    }, 500);
    $('[data-widget="pushmenu"]').PushMenu('collapse');
});
$(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
    setTimeout(function () {
        $(".dataTables_scrollBody").floatingScroll("update")
    }, 500);
});

// Форматируем вводимые в фильтр данные
function strip_tags(html){
    return html.replace(/<.*?>/g, '');
}

// Подсветка выбранных фильтров в шапке таблицы
function colorFilters() {
    $('table.table.table-subscribes thead > tr.filters > th > input.input-filter-subscribesTable').each(function(){
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
    let subscribesTable = $("#subscribesTable").DataTable({
        ajax: 'indexJSON',
        processing: true,
        columns: [
            { data: 'checkbox' },
            {
                data: {
                    _: 'subscribe_date.display',
                    sort: 'subscribe_date.order',
                },
            },
            {
                data: {
                    _: 'status.display',
                    sort: 'status.order',
                },
            },
            {
                data: {
                    _: 'periodicity.display',
                    sort: 'periodicity.order',
                },
            },
            { data: 'subscribe_cost_str' },
            { data: 'subscriber_name' },
            { data: 'subscriber_email' },
            { data: 'subscriber_phone_str' },
            {
                data: {
                    _: 'pref_acc.display',
                    filter: 'pref_acc.filter',
                },
            },
            { data: 'm1' },
            { data: 'm2' },
            { data: 'm3' },
            { data: 'm4' },
            { data: 'm5' },
            { data: 'm6' },
            { data: 'm7' },
            {
                data: {
                    _: 'type.display',
                    sort: 'type.order',
                },
            },
            {
                data: {
                    _: 'size_top.display',
                    sort: 'size_top.order',
                },
            },
            {
                data: {
                    _: 'size_bottom.display',
                    sort: 'size_bottom.order',
                },
            },
            {
                data: {
                    _: 'size_height.display',
                    sort: 'size_height.order',
                },
            },
            {
                data: {
                    _: 'size_foot.display',
                    sort: 'size_foot.order',
                },
            },
            {
                data: {
                    _: 'delivery_type.display',
                    sort: 'delivery_type.order',
                },
            },
            { data: 'delivery_addr' },
            { data: 'edit_btn' },
        ],
        createdRow: function( row, data ) {
            $( row ).find('td:eq(1)').attr('data-order', data.subscribe_date.order);
            $( row ).find('td:eq(2)').attr('data-order', data.status.order);
            $( row ).find('td:eq(3)').attr('data-order', data.periodicity.order);
            $( row ).find('td:eq(8)').attr('data-filter', data.pref_acc.filter);
            $( row ).find('td:eq(16)').attr('data-order', data.type.order);
            $( row ).find('td:eq(17)').attr('data-order', data.size_top.order);
            $( row ).find('td:eq(18)').attr('data-order', data.size_bottom.order);
            $( row ).find('td:eq(19)').attr('data-order', data.size_height.order);
            $( row ).find('td:eq(20)').attr('data-order', data.size_foot.order);
            $( row ).find('td:eq(21)').attr('data-order', data.delivery_type.order);
        },
        stateSave: true,
        paging: false,
        info: true,
        searching: true,
        responsive: false,
        scrollX: true,
        scrollCollapse: true,
        fixedHeader: true,
        fixedColumns: {
            left: 7,
            right: 1
        },
        columnDefs: [
            {
                orderable: false,
                searchable: false,
                targets: [0, -1]
            }
        ],
        orderCellsTop: true,
        order: [[ 1, "desc" ]],
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
                            api.fixedHeader.adjust();
                        });
                });
            completeSelectOptions(api);
            setDefaultFilter();
            api.columns.adjust();
            // Если есть сохранённые фильтры, пересчитываем select-ы
            let state = api.state.loaded();
            if (state) {
                updateSelectOptions(api);
                setTimeout(function(){api.fixedHeader.adjust();},200);
            }
            cssAdjust();
            initComplete = true;
        },
        drawCallback: function () {
            let api = this.api();
            api.columns.adjust();
            if (initComplete === true) cssAdjust();
        },
        stateLoaded: function stateLoadedCallback(settings, state) {
            state.columns.forEach(function (column, index) {
                if (index > 0 && column.search.search !== 'NaN') {
                    $('#subscribesTable > thead > tr.filters > th:eq('+index+') > input[type="search"]').val(column.search.search).data('prev',column.search.search);
                }
            });
            colorFilters();
        }
    });

    // Позиционирование закреплённых столбцов в шапке
    function cssAdjust() {
        let header = subscribesTable.table().header();
        if (window.matchMedia("(min-width: 992px)").matches) {
            subscribesTable.fixedColumns().left(7);
            for (let i = 0; i < 7; i++) {
                let col1_CSS_left = $(header)
                    .find("tr")
                    .eq(0)
                    .find("th")
                    .eq(i)
                    .css("left");

                $(header)
                    .find("tr")
                    .eq(1)
                    .find("th")
                    .eq(i)
                    .css({ left: col1_CSS_left, position: "sticky", "z-index": 1 });
            }
        } else {
            subscribesTable.fixedColumns().left(0);
            for (let i = 0; i < 7; i++) {
                $(header)
                    .find("tr")
                    .eq(1)
                    .find("th")
                    .eq(i)
                    .css({ left: 0, position: "relative", "z-index": 0 });
            }
        }
    }

    window.addEventListener("resize", function () {
        if (initComplete === true) cssAdjust();
    });

    // Фильтр по-умолчанию
    $('.filter_default').click(function(){
        subscribesTable
            .columns()
            .eq(0)
            .each(function (colIdx) {
                let val = '';
                let inputSelect = $('select', $('.filters th').eq($(subscribesTable.column(colIdx).header()).index()));
                if (inputSelect.length === 1) {
                    if (inputSelect.data('default')) {
                        val = inputSelect.data('default');
                    }
                }
                subscribesTable
                    .column(colIdx)
                    .search(val, true, false);
            });
        subscribesTable
            .order([1, 'desc'])
            .search('')
            .draw();
        window.location.reload();
    });

    // Сброс фильтров
    $('.filter_cancel').click(function(){
        subscribesTable.state.clear();
        window.location.reload();
    });

    $(window).focus(function() {
        subscribesTable.draw();
        subscribesTable.fixedHeader.adjust();
    });

    let group_action_buttons = $('#group_action_buttons');
    $('#subscribesTable_filter')
        .closest('.row')
        .find('.col-sm-12.col-md-6:first-child')
        .html(group_action_buttons.html());
    group_action_buttons.remove();

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

// Сборка подписок: форма сборки
content_wrapper.on('click','#showAssemblyFormBtn',function(){
    $('#assemblyFormModal').modal('show');
    let form = $('#assemblyForm');
    form.find('.assemblyForm_subscribe_id').remove();
    $.each($('input.subscribe_checkbox:checked'), function(){
        form.append('<input type="hidden" class="assemblyForm_subscribe_id" name="subscribe_id[]" value="' + $(this).val() + '" />');
    });
});
$().ready(function () {
    // Месяц, за который выполняется сборка
    $('#assembly_month').datetimepicker({
        locale: 'ru',
        format: 'MMMM YYYY'
    });
});

// Сборка подписок
$(function() {
    $("#assemblyForm").on("submit", function() {
        $("#assemblyForm .text-danger").remove();
        $("#assemblyForm .is-invalid").removeClass('is-invalid');
        $.post($(this).attr("action"), $(this).serialize(), function(data) {
            if (parseInt(data.result,10) === 1) {
                location.href = data.redirect;
            } else {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    icon: 'fa fa-exclamation-triangle fa-lg',
                    title: 'Ошибка',
                    body: data.message
                });
            }
        }).fail(function(response) {
            let erroJson = response.responseJSON.errors,
                arErrors = [];
            for (let err in erroJson) {
                for (let errstr of erroJson[err]) {
                    let input = $("#assemblyForm [name='" + err + "']");
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


// Экспорт в excel
content_wrapper.on('click','#excelExportBtn',function(){
    let form = $('#excelExportForm');
    form.find('.excelExportForm_subscribe_id').remove();
    $.each($('input.subscribe_checkbox:checked'), function(){
        form.append('<input type="hidden" class="excelExportForm_subscribe_id" name="subscribe_id[]" value="' + $(this).val() + '" />');
    });
    if (form.find('.excelExportForm_subscribe_id').length > 0) {
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
