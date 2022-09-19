@extends('admin.layouts.main')

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribers.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Подписчики BLACKBASE</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Подписчики BLACKBASE</li>
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
                        <table id="subscribersTable" class="table table-bordered table-hover table-striped basic_table table-subscribers">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Мейл</th>
                                <th>Телефон</th>
                                <th>Подписки</th>
                                <th rowspan="2">
                                    <button class="btn btn-sm btn-secondary filter_cancel" title="Сбросить фильтр и сортировку"><i class="fa fa-times"></i></button>
                                </th>
                            </tr>
                            <tr class="filters">
                                <th>
                                    <input type="search" class="form-control input-filter-subscribersTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribersTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribersTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribersTable" data-prev="">
                                </th>
                                <th>
                                    <input type="search" class="form-control input-filter-subscribersTable" data-prev="">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->user_id }}</td>
                                    <td>{{ $subscriber->name }}</td>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ $subscriber->phone_str }}</td>
                                    <td>{{ $subscriber->subscribe_names }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('admin.subscribers.edit', $subscriber->user_id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Мейл</th>
                                <th>Телефон</th>
                                <th>Подписки</th>
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
    <script src="{{ assetVersioned('dist/js/pages/subscribers.js') }}"></script>
@endsection
