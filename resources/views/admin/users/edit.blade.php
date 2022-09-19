@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Редактирование пользователя {{ $user->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Пользователи</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-block btn-info"><i
                                    class="fa fa-arrow-left mr-2"></i> К списку пользователей</a>
                    </div>
                </div>
                <form action="{{ route('admin.user.update', $user->id) }}" method="post" class="pb-5">
                    @csrf
                    @method('patch')
                    <div class="form-group col-lg-6 pl-0">
                        <label>Имя</label>
                        <input type="text" class="form-control" name="name" placeholder="Имя пользователя" value="{{ old('name', $user->name) }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6 pl-0">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6 pl-0">
                        <label>Выберите тип пользователя</label>
                        <select class="form-control" name="role">
                            @foreach($roles as $id => $role)
                                <option value="{{ $id }}"
                                        {{ $id == old('role', $user->role) ? ' selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 ml-0 pl-0">
                        <blockquote class="ml-0">
                            <p>Менеджер может только управлять заявками по подпискам: отвечать на них и закрывать.</p>
                            <p>Администратору доступны все функции данной панели управления, в том числе упарвление пользователями.</p>
                        </blockquote>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="submit" class="btn btn-primary" value="Обновить">
                </form>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
