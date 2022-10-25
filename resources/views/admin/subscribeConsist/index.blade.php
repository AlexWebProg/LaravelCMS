@extends('admin.layouts.main')

@section('submenu')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="col-12 d-flex p-0">
            <ul class="nav nav-pills">
                @foreach($arPlannedDataLine as $arPlannedDataLineMonth)
                    <li class="nav-item">
                        <a href="{{ route('admin.subscribeConsist.index', $arPlannedDataLineMonth['int']) }}" class="nav-link {{ (request()->route()->named('admin.subscribeConsist.*') && request()->route()->parameters['month'] == $arPlannedDataLineMonth['int']) ? 'active' : '' }}">
                            <i class="fa fa-calendar-check-o nav-icon" aria-hidden="true"></i>
                            {{ $arPlannedDataLineMonth['str'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribeConsist.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Сборка подписок {{ monthIntToStr($month) }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Подписки</li>
                        <li class="breadcrumb-item active">Сборка подписок {{ monthIntToStr($month) }}</li>
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
                                <button id="PDFExportBtn" class="btn btn-success group_action_button" disabled="disabled"><i class="fa fa-file-pdf-o mr-2 d-none d-sm-inline" aria-hidden="true"></i>Печать</button>
                                <button id="showSendFormBtn" class="btn btn-primary group_action_button" disabled="disabled"><i class="fa fa-envelope-o mr-2 d-none d-sm-inline" aria-hidden="true"></i>Отправка</button>
                            </div>
                        </div>
                        <table id="subscribeConsistTable" class="table table-bordered table-hover table-striped basic_table table-sc" data-month="{{ $month }}">
                            <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Подписка</th>
                                <th>Сборка</th>
                                <th>Сумма подписки</th>
                                <th>Подписчик</th>
                                <th>Учёт предп</th>
                                <th>Размеры</th>
                                <th>Состав</th>
                                <th>Отправка</th>
                                <th rowspan="2">
                                    <button class="btn btn-sm btn-secondary filter_cancel" title="Сбросить фильтр и сортировку"><i class="fa fa-times"></i></button>
                                </th>
                            </tr>

                            <tr class="filters">
                                <th>
                                    <input type="checkbox" id="main_checkbox" value="1">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribeConsistTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeConsistTable" data-prev="">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribeConsistTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeConsistTable" data-prev="">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribeConsistTable" multiple="multiple"></select>
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeConsistTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeConsistTable" data-prev="">
                                </th>
                                <th>
                                    <select class="form-control input-filter-subscribeConsistTable" multiple="multiple"></select>
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Подписка</th>
                                <th>Сборка</th>
                                <th>Сумма подписки</th>
                                <th>Подписчик</th>
                                <th>Учёт предп</th>
                                <th>Размеры</th>
                                <th>Состав</th>
                                <th>Отправка</th>
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
                                <input type="text" class="form-control" name="sent_month" value="{{ monthIntToStr($month) }}" placeholder="Месяц, за который выполняется отправка ..." required="required" readonly/>
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

    </div>
    <!-- /.content-wrapper -->

    <form id="PDFExportForm" action="{{ route('admin.subscribeConsist.PDFExport', $month) }}" method="post">
        @csrf
    </form>

@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeConsistIndex.js') }}"></script>
@endsection
