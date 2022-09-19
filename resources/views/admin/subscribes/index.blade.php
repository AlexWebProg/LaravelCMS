@extends('admin.layouts.main')

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribes.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Подписки BLACKBASE</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Подписки BLACKBASE</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pl-0 pr-0">
                <div class="card">
                    <div class="card-body pl-0 pr-0">
                        <div id="group_action_buttons">
                            <div id="group_action_buttons_content">
                                <span class="mr-2">с отм<span class="d-none d-sm-inline">еченными</span>:</span>
                                <button id="excelExportBtn" class="btn btn-success group_action_button" disabled="disabled"><i class="fa fa-file-excel-o mr-2 d-none d-sm-inline" aria-hidden="true"></i>Экспорт</button>
                                <button id="showSendFormBtn" class="btn btn-primary group_action_button" disabled="disabled"><i class="fa fa-envelope-o mr-2 d-none d-sm-inline" aria-hidden="true"></i>Отправка</button>
                            </div>
                        </div>
                        <table id="subscribesTable" class="table table-bordered table-hover table-striped basic_table table-subscribes">
                            <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Дата подписки</th>
                                <th>Статус</th>
                                <th>Периодичность</th>
                                <th>Сумма подписки</th>
                                <th>Имя</th>
                                <th>Мейл</th>
                                <th>Телефон</th>
                                <th>Учёт предп</th>
                                @foreach($arPlannedDataLine as $arPlannedSendMonth)
                                    <th>{{ $arPlannedSendMonth['str'] }}</th>
                                @endforeach
                                <th>Подписка</th>
                                <th>Верх</th>
                                <th>Низ</th>
                                <th>Рост</th>
                                <th>Стопа</th>
                                <th>Тип доставки</th>
                                <th>Адрес доставки</th>
                                <th rowspan="2">
                                    <button class="btn btn-sm btn-info filter_default mb-2" title="Применить фильтр и сортировку по-умолчанию"><i class="fa fa-filter"></i></button>
                                    <button class="btn btn-sm btn-secondary filter_cancel" title="Сбросить фильтр и сортировку"><i class="fa fa-times"></i></button>
                                </th>
                            </tr>
                            <tr class="filters">
                                <th>
                                    <input type="checkbox" id="main_checkbox" value="1">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribesTable" data-prev="">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple" data-default="^Активна$"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribesTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribesTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribesTable" data-prev="">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                @foreach($arPlannedDataLine as $arPlannedSendMonth)
                                    <th>
                                        <select class="form-control input-filter-subscribesTable plannedSendMonthSelect" multiple="multiple"></select>
                                    </th>
                                @endforeach
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribesTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribesTable" data-prev="">
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Дата подписки</th>
                                <th>Статус</th>
                                <th>Периодичность</th>
                                <th>Сумма подписки</th>
                                <th>Имя</th>
                                <th>Мейл</th>
                                <th>Телефон</th>
                                <th>Учёт предп</th>
                                @foreach($arPlannedDataLine as $arPlannedSendMonth)
                                    <th>{{ $arPlannedSendMonth['str'] }}</th>
                                @endforeach
                                <th>Подписка</th>
                                <th>Верх</th>
                                <th>Низ</th>
                                <th>Рост</th>
                                <th>Стопа</th>
                                <th>Тип доставки</th>
                                <th>Адрес доставки</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <div class="modal fade" id="sendFormModal">
            <form id="sendForm" action="{{ route('admin.subscribes.sendMany') }}" method="post">
                @csrf
                @method('patch')
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Отправка выбранных подписок</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="sending_date">Дата отправки</label>
                                <input type="text" class="form-control datetimepicker-input" id="sending_date" data-toggle="datetimepicker" data-target="#sending_date" name="sending_date" value="{{ date('d.m.Y H:i') }}" placeholder="Дата отправки ..." required="required"/>
                            </div>
                            <div class="form-group">
                                <label for="sender">Отправитель</label>
                                <input type="text" class="form-control" id="sender" name="sender" value="{{ auth()->user()->name }}" placeholder="Отправитель ..." required="required"/>
                            </div>
                            <div class="form-group">
                                <label for="sent_month">Месяц, за который выполняется отправка</label>
                                <input type="text" class="form-control datetimepicker-input" id="sent_month" data-toggle="datetimepicker" data-target="#sent_month" name="sent_month" value="{{ $arPlannedDataLine[0]['str'] }}" placeholder="Месяц, за который выполняется отправка ..." required="required"/>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="reset" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times mr-2 d-none d-sm-inline" aria-hidden="true"></i>Отмена</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2 d-none d-sm-inline" aria-hidden="true"></i>Отправить<span class="d-none d-sm-inline"> подписки</span></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->

        <form id="excelExportForm" action="{{ route('admin.subscribes.excelExport') }}" method="post">
        @csrf
        </form>

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribes.js') }}"></script>
@endsection
