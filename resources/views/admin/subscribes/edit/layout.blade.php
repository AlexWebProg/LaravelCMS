@extends('admin.layouts.main')

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribe.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $subscribe->subscribeTypeSetting->value }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribes.index') }}">Подписки</a></li>
                        <li class="breadcrumb-item active">{{ $subscribe->subscribeTypeSetting->value }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content mb-3">
            <ul class="nav nav-tabs subscribe_form_tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ (empty(request()->route()->parameters['action_type'])) ? 'active' : '' }}" href="{{ route('admin.subscribes.edit', $subscribe->id) }}" role="tab">Основное</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'history') ? 'active' : '' }}" href="{{ route('admin.subscribes.edit', [$subscribe->id, 'history']) }}" role="tab">История</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'sending') ? 'active' : '' }}" href="{{ route('admin.subscribes.edit', [$subscribe->id, 'sending']) }}" role="tab">Отправка</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'freeze') ? 'active' : '' }}" href="{{ route('admin.subscribes.edit', [$subscribe->id, 'freeze']) }}" role="tab">{{ ($subscribe->status === 'Заморожена') ? 'Разморозка' : 'Заморозка' }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'reject') ? 'active' : '' }}" href="{{ route('admin.subscribes.edit', [$subscribe->id, 'reject']) }}" role="tab">{{ ($subscribe->status === 'Отменена') ? 'Активация' : 'Отмена' }}</a>
                </li>
            </ul>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('subscribers_form_section')
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeForm.js') }}"></script>
@endsection
