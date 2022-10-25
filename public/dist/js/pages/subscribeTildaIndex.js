// Таблица заказов подписок из Тильды
$(function () {
    $("#subscribesTildaTable").DataTable({
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
        order: [[1, 'desc']],
        "responsive": false,
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
