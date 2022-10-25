@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $subscribeType->value }} {{ monthIntToStr($month) }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribeTypeConsist.index', $month) }}">Состав подписок {{ monthIntToStr($month) }}</a></li>
                        <li class="breadcrumb-item active">{{ $subscribeType->value }}</li>
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
                        <form id="main_form" action="{{ route('admin.subscribeTypeConsist.update', [$subscribeType->id, $month]) }}" method="post">
                            @csrf
                            @method('patch')
                            <div id="subscribe_consist_rows" class="sortable_rows form_sortable_rows">
                                @foreach($subscribeType->subscribeTypeConsistByMonth($month) as $subscribeTypeProduct)
                                    <div class="row m-0">
                                        <div class="col">
                                            <select name="product_id[]" class="form-control product_id">
                                                @foreach($subscribeProducts as $subscribeProduct):
                                                <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]', $subscribeTypeProduct->id) ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="{{ $subscribeTypeProduct->pivot->qnt }}" required min="0" />
                                        </div>
                                        <div class="col-2 col-sm-1 text-center">
                                            <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
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
                            <button id="add_subscribe_consist_button" class="btn btn-info float-right">
                                <i class="fa fa-plus mr-2" title="Кликните для добавления ещё одного товара" aria-hidden="true"></i>Добавить
                            </button>
                            <div id="new_subscribe_consist_block" class="d-none">
                                <div class="row m-0">
                                    <div class="col">
                                        <select name="product_id[]" class="form-control product_id">
                                            @foreach($subscribeProducts as $subscribeProduct):
                                            <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]') ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="1" required min="0" />
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
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
    <script src="{{ assetVersioned('dist/js/pages/subscribeTypeConsist.js') }}"></script>
@endsection
