@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $subscribe->subscribeTypeSetting->value }}. Сборка за {{ monthIntToStr($month) }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribeConsist.index', $month) }}">Сборка подписок {{ monthIntToStr($month) }}</a></li>
                        <li class="breadcrumb-item active">{{ $subscribe->subscribeTypeSetting->value }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary default-collapsed">
                            <div class="card-header">
                                <h3 class="card-title">Параметры подписки</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body pb-0">
                                <dl>
                                    <dt>Статус</dt>
                                    <dd>{{ $subscribe->status }}</dd>

                                    <dt>Тип подписки</dt>
                                    <dd>{{ $subscribe->subscribeTypeSetting->value }}</dd>

                                    <dt>Сумма подписки</dt>
                                    <dd>{{ $subscribe->subscribe_cost_str }}</dd>

                                    <dt>Учёт предпочтений</dt>
                                    <dd>{{ empty($subscribe->pref_acc_id) ? 'нет' : $subscribe->prefAccSetting->value }}</dd>

                                    <dt>Исключить</dt>
                                    <dd>{{ empty($subscribe->exclude) ? 'нет' : $subscribe->exclude }}</dd>

                                    <dt>Наш комментарий</dt>
                                    <dd>{{ empty($subscribe->comment_manager) ? 'нет' : $subscribe->comment_manager }}</dd>

                                    <dt>Комментарий подписчика</dt>
                                    <dd>{{ empty($subscribe->comment_subscriber) ? 'нет' : $subscribe->comment_subscriber }}</dd>

                                    <dt>Дата подписки</dt>
                                    <dd>{{ $subscribe->subscribe_date }}</dd>
                                </dl>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="{{ route('admin.subscribes.edit', $subscribe->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-address-card-o mr-2"></i> Перейти к подписке
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card card-purple default-collapsed">
                            <div class="card-header">
                                <h3 class="card-title">Размеры</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <dl>
                                    <dt>Верх</dt>
                                    <dd>{{ $subscribe->sizeTopSetting->value }}</dd>

                                    <dt>Низ</dt>
                                    <dd>{{ $subscribe->sizeBottomSetting->value }}</dd>

                                    <dt>Рост</dt>
                                    <dd>{{ $subscribe->sizeHeightSetting->value }}</dd>

                                    <dt>Стопа</dt>
                                    <dd>{{ $subscribe->sizeFootSetting->value }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-info default-collapsed">
                            <div class="card-header">
                                <h3 class="card-title">Подписчик</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <dl>
                                    <dt>Имя</dt>
                                    <dd>{{ $subscribe->subscriber->name }}</dd>

                                    <dt>Мейл</dt>
                                    <dd>{{ $subscribe->subscriber->email }}</dd>

                                    <dt>Телефон</dt>
                                    <dd>{{ $subscribe->subscriber->phone_str }}</dd>
                                </dl>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="{{ route('admin.subscribers.edit', $subscribe->subscriber->user_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-user mr-2"></i> Перейти в профиль
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card card-lightblue default-collapsed">
                            <div class="card-header">
                                <h3 class="card-title">Параметры отправки</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <dl>
                                    <dt>Периодичность</dt>
                                    <dd>{{ $subscribe->periodicitySetting->value }}</dd>

                                    <dt>Даты отправки</dt>
                                    <dd>{{ $subscribe->sendDatesSetting->value }}</dd>

                                    <dt>Следующая отправка</dt>
                                    <dd>{{ $subscribe->next_send_month }}</dd>

                                    <dt>Тип доставки</dt>
                                    <dd>{{ $subscribe->deliverySetting->value }}</dd>

                                    <dt>Адрес доставки</dt>
                                    <dd>{{ $subscribe->delivery_addr }}</dd>
                                </dl>
                                <hr/>
                                <p><b>Планируемые отправки:</b></p>
                                @if (count($subscribe->estimated_sends))
                                    <dl class="row">
                                        @foreach ($subscribe->estimated_sends as $arEstimatedSend)
                                            <dt class="col-6 text-right">{{ $arEstimatedSend['str'] }}</dt>
                                            <dd class="col-6">{{ $subscribe->sendDatesSetting->value }}</dd>
                                        @endforeach
                                    </dl>
                                @else
                                    <p class="text-center"><b>нет</b></p>
                                @endif
                                <hr/>
                                <dl class="row">
                                    <dd class="col-6 text-right">Дата отправки для подписчика:</dd>
                                    <dt class="col-6">{{ $subscribe->send_date }}</dt>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pb-5">
                    <div class="col-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Состав подписки</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="main_form" action="{{ route('admin.subscribeConsist.update', [$subscribe->id, $month]) }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <div id="subscribe_consist_rows" class="sortable_rows form_sortable_rows">
                                        @foreach($subscribe->subscribeConsistByMonth($month) as $subscribeConsistProduct)
                                            <div class="row m-0">
                                                <div class="col">
                                                    <select name="product_id[]" class="form-control product_id">
                                                        @foreach($subscribeProducts as $subscribeProduct):
                                                        <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]', $subscribeConsistProduct->id) ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="{{ $subscribeConsistProduct->pivot->qnt }}" required min="0" />
                                                </div>
                                                <div class="col-2 col-sm-1 text-center">
                                                    <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-2 col-sm-1 text-center">
                                                    <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input id="main_form_submit" type="submit" class="d-none">
                                </form>
                            </div>
                            <div class="card-footer">
                                <button id="add_subscribe_consist_button" class="btn btn-info float-right">
                                    <i class="fa fa-plus mr-2" title="Кликните для добавления ещё одного товара" aria-hidden="true"></i>Добавить
                                </button>
                            </div>
                        </div>

                        <div id="new_subscribe_consist_block" class="d-none">
                            <div class="row m-0">
                                <div class="col">
                                    <select name="product_id[]" class="form-control product_id">
                                        @foreach($subscribeProducts as $subscribeProduct):
                                        <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]') ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="1" required min="0" />
                                </div>
                                <div class="col-2 col-sm-1 text-center">
                                    <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
                                </div>
                                <div class="col-2 col-sm-1 text-center">
                                    <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>


                        {{--<form id="main_form" action="{{ route('admin.subscribeConsist.update', [$subscribe->id, $month]) }}" method="post">
                            @csrf
                            @method('patch')
                            <div id="subscribe_consist_rows" class="sortable_rows form_sortable_rows">
                                @foreach($subscribe->subscribeConsistByMonth($month) as $subscribeConsistProduct)
                                    <div class="row m-0">
                                        <div class="col">
                                            <select name="product_id[]" class="form-control product_id">
                                                @foreach($subscribeProducts as $subscribeProduct):
                                                <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]', $subscribeConsistProduct->id) ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="{{ $subscribeConsistProduct->pivot->qnt }}" required min="0" />
                                        </div>
                                        <div class="col-2 col-sm-1 text-center">
                                            <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-2 col-sm-1 text-center">
                                            <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input id="main_form_submit" type="submit" class="d-none">
                        </form>
                        <div class="add_item_button_block">
                            <button id="add_subscribe_consist_button" class="btn btn-info float-right">
                                <i class="fa fa-plus mr-2" title="Кликните для добавления ещё одного товара" aria-hidden="true"></i>Добавить
                            </button>
                            <div id="new_subscribe_consist_block" class="d-none">
                                <div class="row m-0">
                                    <div class="col">
                                        <select name="product_id[]" class="form-control product_id">
                                            @foreach($subscribeProducts as $subscribeProduct):
                                            <option value="{{ $subscribeProduct->id }}" {{ $subscribeProduct->id == old('product_id[]') ? ' selected' : '' }}>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="number" class="form-control" placeholder="Кол-во ..." name="qnt[]" value="1" required min="0" />
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-trash-o fa-lg text-danger cursor-pointer delete_subscribe_consist_button" title="Кликните для удаления" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-2 col-sm-1 text-center">
                                        <i class="fa fa-bars cursor-pointer sortable_handle" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>--}}


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
    <script src="{{ assetVersioned('dist/js/pages/subscribeConsistEdit.js') }}"></script>
@endsection
