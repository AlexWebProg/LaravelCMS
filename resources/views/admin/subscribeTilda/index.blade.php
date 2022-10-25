@extends('admin.layouts.main')

@section('submenu')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="col-12 d-flex p-0">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{{ route('admin.subscribeTilda.index', 0) }}" class="nav-link {{ (request()->route()->named('admin.subscribeTilda.index') && request()->route()->parameters['processed'] == 0) ? 'active' : '' }}">
                        <i class="fa fa-star nav-icon" aria-hidden="true"></i>
                        Новые
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscribeTilda.index', 1) }}" class="nav-link {{ (request()->route()->named('admin.subscribeTilda.index') && request()->route()->parameters['processed'] == 1) ? 'active' : '' }}">
                        <i class="fa fa-check nav-icon" aria-hidden="true"></i>
                        Обработанные
                    </a>
                </li>
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
                    <h1 class="mt-0">Заказы подписок из Тильды</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Подписки</li>
                        <li class="breadcrumb-item active">Заказы подписок из Тильды</li>
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
                                <table id="subscribesTildaTable" class="table table-bordered table-hover table-striped dataTable basic_table" aria-describedby="subRequestsTable_info">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Создан</th>
                                        <th class="text-center">Подписчик</th>
                                        <th class="text-center">Подписка</th>
                                        <th class="text-center">Доставка</th>
                                        @if ( request()->route()->parameters['processed'] == 0 )
                                            <th class="text-center"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ordersFromTilda as $orderFromTilda)
                                        <tr>
                                            <td class="text-center">{{ $orderFromTilda->id }}</td>
                                            <td class="text-center">{{ $orderFromTilda->created_str }}</td>
                                            <td class="text-center">
                                                {{ $orderFromTilda->data['Name'] }}<br/>
                                                {{ $orderFromTilda->data['email'] }}<br/>
                                                {{ $orderFromTilda->data['phone'] }}
                                            </td>
                                            <td class="text-center text-nowrap">
                                                Сумма: {{ $orderFromTilda->data['payment']['amount'] }}<br/>
                                                Тип подписки: {{ $orderFromTilda->data['subscribe']['Тип подписки'] }}<br/>
                                                Периодичность: {{ $orderFromTilda->data['subscribe']['Периодичность'] }}<br/>
                                                Размер верха: {{ $orderFromTilda->data['subscribe']['Размер верха'] }}<br/>
                                                Размер низа: {{ $orderFromTilda->data['subscribe']['Размер низа'] }}<br/>
                                                Ваш рост: {{ $orderFromTilda->data['subscribe']['Ваш рост'] }}<br/>
                                                Размер стопы: {{ $orderFromTilda->data['subscribe']['Размер стопы'] }}<br/>
                                                Учет предпочтений: {{ $orderFromTilda->data['subscribe']['Учет предпочтений'] }}<br/>
                                            </td>
                                            <td class="text-center">
                                                Тип доставки: {{ $orderFromTilda->data['payment']['delivery'] }}<br/>
                                                Адрес доставки: {{ $orderFromTilda->data['payment']['delivery_address'] }}
                                                @if (!empty($orderFromTilda->data['payment']['delivery_price']))
                                                    <br/>Стоимость: {{ $orderFromTilda->data['payment']['delivery_price'] }}
                                                @endif
                                                @if (!empty($orderFromTilda->data['payment']['delivery_fio']))
                                                    <br/>ФИО: {{ $orderFromTilda->data['payment']['delivery_fio'] }}
                                                @endif
                                                @if (!empty($orderFromTilda->data['payment']['delivery_comment']))
                                                    <br/>Комментарий: {{ $orderFromTilda->data['payment']['delivery_comment'] }}
                                                @endif
                                            </td>
                                            @if ( request()->route()->parameters['processed'] == 0 )
                                                <td class="text-center"><a class="btn btn-primary btn-sm" href="{{ route('admin.subscribeTilda.createSubscribe', $orderFromTilda->id) }}"><i class="fa fa-plus" aria-hidden="true" title="Создать подписку"></i></a></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Создана</th>
                                        <th class="text-center">Подписчик</th>
                                        <th class="text-center">Подписка</th>
                                        <th class="text-center">Доставка</th>
                                        @if ( request()->route()->parameters['processed'] == 0 )
                                            <th class="text-center"></th>
                                        @endif
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

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeTildaIndex.js') }}"></script>
@endsection
