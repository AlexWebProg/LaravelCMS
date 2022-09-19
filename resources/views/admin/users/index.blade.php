@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Пользователи панели управления подписками</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Пользователи</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                @can('update-users')
                <div class="row mb-3">
                    <div class="col-md-3">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-block btn-primary">Добавить</a>
                    </div>
                </div>
                @endcan
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th class="d-none d-md-table-cell">ID</th>
                                        <th>Имя</th>
                                        <th class="d-none d-md-table-cell">Email</th>
                                        <th class="d-none d-md-table-cell">Тип</th>
                                        <th @can('update-users') colspan="3" @endcan class="text-center">Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="d-none d-md-table-cell">{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                                            <td class="d-none d-md-table-cell">{{ $user->role_text }}</td>
                                            <td class="text-center"><a href="{{ route('admin.user.show', $user->id) }}"><i
                                                            class="fa fa-eye text-info"></i></a></td>
                                            @can('update-users')
                                            <td class="text-center"><a href="{{ route('admin.user.edit', $user->id) }}"><i
                                                            class="fa fa-pencil text-success"></i></a></td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.user.delete', $user->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i data-user_name="{{ $user->name }}" class="confirm_user_delete fa fa-trash text-danger cursor-pointer"></i>
                                                </form>
                                            </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
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
