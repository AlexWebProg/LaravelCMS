@extends('admin.layouts.main')

@section('submenu')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="col-12 d-flex p-0">
            <ul class="nav nav-pills">
                @foreach($arPlannedDataLine as $arPlannedDataLineMonth)
                    <li class="nav-item">
                        <a href="{{ route('admin.subscribeTypeConsist.index', $arPlannedDataLineMonth['int']) }}" class="nav-link {{ (request()->route()->named('admin.subscribeTypeConsist.*') && request()->route()->parameters['month'] == $arPlannedDataLineMonth['int']) ? 'active' : '' }}">
                            <i class="fa fa-calendar-check-o nav-icon" aria-hidden="true"></i>
                            {{ $arPlannedDataLineMonth['str'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Состав типов подписок {{ monthIntToStr($month) }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Подписки</li>
                        <li class="breadcrumb-item active">Состав типов подписок {{ monthIntToStr($month) }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card p-0">
                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover table-striped basic_table">
                            <thead>
                            <tr>
                                <th>Подписка</th>
                                <th>Состав</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribeTypes as $subscribeType)
                                <tr>
                                    <td>{{ $subscribeType->value }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                        @foreach($subscribeType->subscribeTypeConsistByMonth($month) as $subscribeTypeProduct)
                                            <li>{{ $subscribeTypeProduct->article }}, {{ $subscribeTypeProduct->name }}: {{ $subscribeTypeProduct->pivot->qnt }}шт</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('admin.subscribeTypeConsist.edit', [$subscribeType->id, $month]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Подписка</th>
                                <th>Состав</th>
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
