@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header pb-0">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Новая подписка</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribes.index') }}">Подписки</a></li>
                        <li class="breadcrumb-item active">Новая подписка</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="bs-stepper mb-3">
                <div class="bs-stepper-header" role="tablist">
                    <div class="step active">
                        <button type="button" class="step-trigger pl-0 pt-0 pb-0">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Выбор подписчика</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step">
                        <button type="button" class="step-trigger pr-0 pt-0 pb-0" onclick="$('#setSubscriberBtn').click();">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Данные подписки</span>
                        </button>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.subscribes.create.setSubscriber') }}" method="post" class="pb-5">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Подписчик</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="user_id">Мейл или телефон</label>
                                    <select id="user_id" name="user_id" class="form-control select2">
                                        @foreach($arSubscribers as $arSubscriber):
                                        <option value="{{ $arSubscriber['user_id'] }}" {{ $arSubscriber['user_id'] == old('user_id') ? ' selected' : '' }}>{{ $arSubscriber['email_or_phone'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div id="user_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary"><i class="fa fa-plus mr-2 d-none d-sm-inline" aria-hidden="true"></i>Создать<span class="d-none d-sm-inline"> нового</span></a>
                                <div class="pull-right"><button id="setSubscriberBtn" type="submit" class="btn btn-success"><i class="fa fa-check mr-2 d-none d-sm-inline" aria-hidden="true"></i>Выбрать<span class="d-none d-sm-inline"> указанного</span></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
