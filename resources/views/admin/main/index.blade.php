@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Заявки по подпискам</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Главная страница</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <div class="row">
                    @foreach($arSubRequestBasicInfo as $arSubRequest)
                        <div class="{{ ($arSubRequest['status_code'] == 'allOpened') ? 'col-lg-4 col-12' : 'col-lg-2 col-6' }}">
                            <div class="small-box bg-{{ $arSubRequest['color_type'] }}">
                                <div class="inner">
                                    <h3>{{ $arSubRequest['requests_qnt'] }}</h3>
                                    <p>{{ $arSubRequest['status_name_many'] }}</p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon {{ $arSubRequest['icon'] }}" aria-hidden="true"></i>
                                </div>
                                <a href="{{ route('admin.subRequests.index', $arSubRequest['status_code']) }}" class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="callout callout-info mt-5">
                    <dl>
                        <dt>Все открытые</dt>
                        <dd>Заявка ждёт наших действий: ответа подписчику или закрытия</dd>

                        <dt>Новые</dt>
                        <dd>Заявки созданы подписчиками и ещё не обработаны нами</dd>

                        <dt>Обработанные</dt>
                        <dd>Мы ответили на заявки, ждём дальнейших действий подписчиков, или закроем заявки через несколько дней</dd>

                        <dt>Обновлённые</dt>
                        <dd>Подписчики добавили свои комментарии к заявкам, ждёт наших ответов</dd>

                        <dt>Закрытые</dt>
                        <dd>Заявки обработаны и закрыты</dd>
                    </dl>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
