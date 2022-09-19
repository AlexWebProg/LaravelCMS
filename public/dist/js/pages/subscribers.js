// Подсветка выбранных фильтров в шапке таблицы
function colorFilters() {
    $('table.table.table-subscribers thead > tr.filters > th > input.input-filter-subscribersTable').each(function(){
        $(this).removeClass('filter-applied');
        if ($(this).val() !== '') $(this).addClass('filter-applied');
    });
}

// Горизонтальная прокрутка таблицы
$().ready(function () {
    setTimeout(function () {
        $(".dataTables_scrollBody").floatingScroll("init")
    }, 500);
});
$(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
    setTimeout(function () {
        $(".dataTables_scrollBody").floatingScroll("update")
    }, 500);
});

$(document).ready(function () {
    let subscribersTable = $("#subscribersTable").DataTable({
        stateSave: true,
        paging: false,
        info: true,
        searching: true,
        responsive: false,
        scrollX: true,
        scrollCollapse: true,
        fixedHeader: true,
        columns: [
            null,
            null,
            null,
            null,
            {
                "render": function(data, type, row){
                    return data.split(",").join("<br/>");
                }
            },
            {
                orderable: false,
                searchable: false
            },


        ],
        orderCellsTop: true,
        order: [[ 0, "desc" ]],
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
                                colorFilters();
                            }
                        });
                });
            colorFilters();
        },
        stateLoaded: function stateLoadedCallback(settings, state) {
            state.columns.forEach(function (column, index) {
                if (index > 0 && column.search.search !== 'NaN') {
                    $('#subscribersTable > thead > tr.filters > th:eq('+index+') > input[type="search"]').val(column.search.search).data('prev',column.search.search);
                }
            });
            colorFilters();
        }
    });

    // Сброс фильтров
    $('.filter_cancel').click(function(){
        subscribersTable.state.clear();
        window.location.reload();
    });

    $('.dataTables_empty').attr('colspan','18');

});
