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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pl-0 pr-0">
                                <table id="subRequestsTable" class="table table-bordered table-hover table-striped dataTable basic_table" aria-describedby="subRequestsTable_info">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Подписчик</th>
                                        <th class="text-center">Подписка</th>
                                        <th class="text-center">Что сделать</th>
                                        <th class="text-center">Создана</th>
                                        <th class="text-center">Обновлена</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subRequests as $subRequest)
                                        <tr>
                                            <td class="text-center">{{ $subRequest->id }}</td>
                                            <td class="text-center">{{ $subRequest->subscribe_info['user_name'] }}</td>
                                            <td class="text-center text-nowrap">{{ $subRequest->subscribe_info['subscribe_name'] }}</td>
                                            <td class="text-center"><div class="rowLimit">{{ $subRequest->actions_text_in_table }}</div></td>
                                            <td class="text-center text-nowrap" data-order="{{ $subRequest->created }}">{{ $subRequest->created_text }}</td>
                                            <td class="text-center text-nowrap" data-order="{{ empty($subRequest->updated) ? $subRequest->created : $subRequest->updated }}">{{ $subRequest->updated_text }}</td>
                                            <td class="text-center"><a class="btn btn-primary btn-sm" href="{{ route('admin.subRequests.show', [$status, $subRequest->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Подписчик</th>
                                        <th class="text-center">Подписка</th>
                                        <th class="text-center">Что сделать</th>
                                        <th class="text-center">Создана</th>
                                        <th class="text-center">Обновлена</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
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
