@extends('admin.layouts.main')

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribeProducts.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Товары из подписок</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Товары из подписок</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="subscribeProductsTable" class="table table-bordered table-hover table-striped basic_table table-subscribeProducts">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Артикул</th>
                                <th rowspan="2">
                                    <button class="btn btn-sm btn-secondary filter_cancel" title="Сбросить фильтр и сортировку"><i class="fa fa-times"></i></button>
                                </th>
                            </tr>
                            <tr class="filters">
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeProductsTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeProductsTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribeProductsTable" data-prev="">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribeProducts as $subscribeProduct)
                                <tr>
                                    <td>{{ $subscribeProduct->id }}</td>
                                    <td>{{ $subscribeProduct->name }}</td>
                                    <td>{{ $subscribeProduct->article }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('admin.subscribeProducts.edit', $subscribeProduct->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Артикул</th>
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

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeProducts.js') }}"></script>
@endsection
