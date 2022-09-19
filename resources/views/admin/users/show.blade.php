@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <div class="d-flex align-items-center">
                        <h1 class="mt-0 mr-2">{{ $user->name }}</h1>
                        @can('update-users')
                        <a class="mr-2" href="{{ route('admin.user.edit', $user->id) }}"><i
                                    class="fa fa-pencil text-success"></i></a>
                        <form action="{{ route('admin.user.delete', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <i data-user_name="{{ $user->name }}" class="confirm_user_delete fa fa-trash text-danger cursor-pointer"></i>
                        </form>
                        @endcan
                    </div>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Пользователи</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-block btn-info"><i
                                    class="fa fa-arrow-left mr-2"></i> К списку пользователей</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <tbody>
                                    <tr>
                                        <td>ID</td>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Имя</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Тип</td>
                                        <td>{{ $user->role_text }}</td>
                                    </tr>
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
