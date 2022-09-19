@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $pageTitle }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row pb-5">
                    <div class="col-12">
                        <form id="main_form" action="{{ route('admin.subscribeSettings.update', $type) }}" method="post">
                            @csrf
                            @method('patch')
                            <div id="subscribe_parameter_rows" class="sortable_rows form_sortable_rows">
                            @foreach($subscribeSettings as $subscribeSetting)
                                <div class="row m-0" data-element_id="{{ $subscribeSetting->id }}">
                                    <div class="col">
                                        <input type="hidden" name="id[]" value="{{ $subscribeSetting->id }}">
                                        <input type="text" class="form-control" placeholder="Значение ..." name="value[]" value="{{ $subscribeSetting->value }}" required />
                                    </div>
                                    @if (in_array($type, ['delivery_type','subscribe_type','pref_acc']))
                                    <div class="col-2">
                                        <input type="number" class="form-control" placeholder="Стоимость ..." name="cost[]" value="{{ $subscribeSetting->cost }}" required min="0" />
                                    </div>
                                    @endif
                                    <div class="col-2 col-sm-1 text-center">
                                        <label class="checkbox-ios">
                                            <input type="checkbox" name="is_active[]" {{ ($subscribeSetting->is_active) ? 'checked' : '' }}>
                                            <span class="checkbox-ios-switch"></span>
                                        </label>
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_parameter_button" title="Кликните для удаления" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <input id="main_form_submit" type="submit" class="d-none">
                        </form>
                        <div class="add_item_button_block">
                            <button id="add_subscribe_parameter_button" class="btn btn-info float-right">
                                <i class="fa fa-plus mr-2" title="Кликните для добавления ещё одного значения" aria-hidden="true"></i>Добавить
                            </button>
                            <div id="new_subscribe_parameter_block" class="d-none">
                                <div class="row m-0" data-element_id="0">
                                    <div class="col">
                                        <input type="hidden" name="id[]" value="0">
                                        <input type="text" class="form-control" placeholder="Значение ..." name="value[]" value="" required />
                                    </div>
                                    @if (in_array($type, ['delivery_type','subscribe_type','pref_acc']))
                                        <div class="col-2">
                                            <input type="number" class="form-control" placeholder="Стоимость ..." name="cost[]" value="" required min="0" />
                                        </div>
                                    @endif
                                    <div class="col-2 col-sm-1 text-center">
                                        <label class="checkbox-ios">
                                            <input type="checkbox" name="is_active[]" checked>
                                            <span class="checkbox-ios-switch"></span>
                                        </label>
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_parameter_button" title="Кликните для удаления" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeSettings.js') }}"></script>
@stop
