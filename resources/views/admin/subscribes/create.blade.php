@extends('admin.layouts.main')

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/subscribe.css') }}">
@endsection

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
                    <div class="step">
                        <button onclick="location.href='{{ route('admin.subscribes.create.chooseSubscriber') }}'" type="button" class="step-trigger pl-0 pt-0 pb-0">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Выбор подписчика</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step active">
                        <button type="button" class="step-trigger pr-0 pt-0 pb-0">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Данные подписки</span>
                        </button>
                    </div>
                </div>
            </div>
            @error('subscribe_tilda_id')
            <div class="alert alert-danger" id="subscribe_tilda_id_error">{{ $message }}</div>
            @enderror
            <form id="main_form" action="{{ route('admin.subscribes.store') }}" method="post" class="pb-5">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Параметры подписки</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Статус</label>
                                    <select id="status" name="status" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        @foreach ($arSettings['status'] as $arStatusSetting)
                                            <option value="{{ $arStatusSetting['value'] }}" {{ $arStatusSetting['value'] == old('status') ? ' selected' : '' }}>{{ $arStatusSetting['value'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subscribe_type_id">Тип подписки</label>
                                    <select id="subscribe_type_id" name="subscribe_type_id" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        @foreach ($arSettings['subscribe_type'] as $arSubscribeTypeSetting)
                                            <option data-cost="{{ $arSubscribeTypeSetting->cost }}" value="{{ $arSubscribeTypeSetting->id }}" {{ $arSubscribeTypeSetting->id == old('subscribe_type_id', $arTildaOrderSettings['type']) ? ' selected' : '' }}>{{ $arSubscribeTypeSetting->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('subscribe_type_id')
                                    <div id="subscribe_type_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="sum">Сумма подписки</label>
                                    <div class="input-group">
                                        <select id="sum_calc_type" name="sum_calc_type" class="form-control custom-select">
                                            <option disabled="">Выберите:</option>
                                            <option value="0" {{ 0 == old('sum_calc_type') ? ' selected' : '' }}>Считать автоматически</option>
                                            <option value="1" {{ 1 == old('sum_calc_type') ? ' selected' : '' }}>Указать вручную</option>
                                        </select>
                                        <input type="text" class="form-control text-right @error('sum') is-invalid @enderror" id="sum" name="sum" value="{{ old('sum') }}" data-init-value="0.00"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-rub" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                    @error('sum_calc_type')
                                    <div id="sum_calc_type_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error('sum')
                                    <div id="sum_error" class="text-danger pull-right">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="pref_acc_id">Учёт предпочтений</label>
                                    <select id="pref_acc_id" name="pref_acc_id" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        <option data-cost="0" value="" {{ empty(old('pref_acc_id')) ? ' selected' : '' }}>нет</option>
                                        @foreach ($arSettings['pref_acc'] as $arPrefAccSetting)
                                            <option data-cost="{{ $arPrefAccSetting->cost }}" value="{{ $arPrefAccSetting->id }}" {{ $arPrefAccSetting->id == old('pref_acc_id', $arTildaOrderSettings['pref_acc']) ? ' selected' : '' }}>{{ $arPrefAccSetting->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('pref_acc_id')
                                    <div id="pref_acc_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Исключить</label>
                                    <textarea class="form-control @error('exclude') is-invalid @enderror" rows="3" name="exclude" placeholder="Исключить ...">{{ old('exclude') }}</textarea>
                                    @error('exclude')
                                    <div id="exclude_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Наш комментарий</label>
                                    <textarea class="form-control @error('comment_manager') is-invalid @enderror" rows="3" name="comment_manager" placeholder="Наш комментарий ...">{{ old('comment_manager') }}</textarea>
                                    @error('comment_manager')
                                    <div id="comment_manager_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Комментарий подписчика</label>
                                    <textarea class="form-control @error('comment_subscriber') is-invalid @enderror" rows="3" name="comment_subscriber" placeholder="Комментарий подписчика ...">{{ old('comment_subscriber', $arTildaOrderSettings['comment_subscriber']) }}</textarea>
                                    @error('comment_subscriber')
                                    <div id="comment_subscriber_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="subscribe_date">Дата подписки</label>
                                    <input type="text" class="form-control datetimepicker-input @error('subscribe_date') is-invalid @enderror" id="subscribe_date" data-toggle="datetimepicker" data-target="#subscribe_date" name="subscribe_date" value="{{ old('subscribe_date', date('d.m.Y H:i',time())) }}" placeholder="Дата подписки ..."/>
                                    @error('subscribe_date')
                                    <div id="subscribe_date_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card card-purple">
                            <div class="card-header">
                                <h3 class="card-title">Размеры</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body form-horizontal">
                                <div class="form-group row">
                                    <label for="size_top_id" class="col-3 col-form-label text-right">Верх</label>
                                    <div class="col-9">
                                        <select id="size_top_id" name="size_top_id" class="form-control custom-select">
                                            <option disabled="">Выберите:</option>
                                            @foreach ($arSettings['size_top'] as $arSizeTopSetting)
                                                <option value="{{ $arSizeTopSetting->id }}" {{ $arSizeTopSetting->id == old('size_top_id', $arTildaOrderSettings['size_top']) ? ' selected' : '' }}>{{ $arSizeTopSetting->value }}</option>
                                            @endforeach
                                        </select>
                                        @error('size_top_id')
                                        <div id="size_top_id_error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="size_bottom_id" class="col-3 col-form-label text-right">Низ</label>
                                    <div class="col-9">
                                        <select id="size_bottom_id" name="size_bottom_id" class="form-control custom-select">
                                            <option disabled="">Выберите:</option>
                                            @foreach ($arSettings['size_bottom'] as $arSizeBottomSetting)
                                                <option value="{{ $arSizeBottomSetting->id }}" {{ $arSizeBottomSetting->id == old('size_bottom_id', $arTildaOrderSettings['size_bottom']) ? ' selected' : '' }}>{{ $arSizeBottomSetting->value }}</option>
                                            @endforeach
                                        </select>
                                        @error('size_bottom_id')
                                        <div id="size_bottom_id_error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="size_height_id" class="col-3 col-form-label text-right">Рост</label>
                                    <div class="col-9">
                                        <select id="size_height_id" name="size_height_id" class="form-control custom-select">
                                            <option disabled="">Выберите:</option>
                                            @foreach ($arSettings['size_height'] as $arSizeHeightSetting)
                                                <option value="{{ $arSizeHeightSetting->id }}" {{ $arSizeHeightSetting->id == old('size_height_id', $arTildaOrderSettings['size_height']) ? ' selected' : '' }}>{{ $arSizeHeightSetting->value }}</option>
                                            @endforeach
                                        </select>
                                        @error('size_height_id')
                                        <div id="size_height_id_error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="size_foot_id" class="col-3 col-form-label text-right">Стопа</label>
                                    <div class="col-9">
                                        <select id="size_foot_id" name="size_foot_id" class="form-control custom-select">
                                            <option disabled="">Выберите:</option>
                                            @foreach ($arSettings['size_foot'] as $arSizeFootSetting)
                                                <option value="{{ $arSizeFootSetting->id }}" {{ $arSizeFootSetting->id == old('size_foot_id', $arTildaOrderSettings['size_foot']) ? ' selected' : '' }}>{{ $arSizeFootSetting->value }}</option>
                                            @endforeach
                                        </select>
                                        @error('size_foot_id')
                                        <div id="size_foot_id_error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-info">
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
                                    <dd>{{ $subscriber->name }}</dd>

                                    <dt>Мейл</dt>
                                    <dd>{{ $subscriber->email }}</dd>

                                    <dt>Телефон</dt>
                                    <dd>{{ $subscriber->phone_str }}</dd>
                                </dl>
                                <input type="hidden" id="user_id" name="user_id" value="{{ $subscriber->user_id }}"/>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.subscribes.create.chooseSubscriber') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-arrow-left mr-2"></i> Выбрать другого
                                </a>
                            </div>
                        </div>

                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Параметры отправки</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status_id">Периодичность</label>
                                    <select id="status_id" name="periodicity_id" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        @foreach ($arSettings['periodicity'] as $arPeriodicitySetting)
                                            <option value="{{ $arPeriodicitySetting->id }}" {{ $arPeriodicitySetting->id == old('periodicity_id', $arTildaOrderSettings['periodicity']) ? ' selected' : '' }}>{{ $arPeriodicitySetting->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('periodicity_id')
                                    <div id="periodicity_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="send_dates_id">Даты отправки</label>
                                    <select id="send_dates_id" name="send_dates_id" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        @foreach ($arSettings['send_dates'] as $arSendDatesSetting)
                                            <option value="{{ $arSendDatesSetting->id }}" {{ $arSendDatesSetting->id == old('send_dates_id') ? ' selected' : '' }}>{{ $arSendDatesSetting->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('send_dates_id')
                                    <div id="send_dates_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="next_send_month">Следующая отправка</label>
                                    <input type="text" class="form-control datetimepicker-input @error('next_send_month') is-invalid @enderror" id="next_send_month" data-toggle="datetimepicker" data-target="#next_send_month" name="next_send_month" value="{{ old('next_send_month') }}" placeholder="Следующая отправка ..."  required="required" />
                                    @error('next_send_month')
                                    <div id="next_send_month_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="delivery_type_id">Тип доставки</label>
                                    <select id="delivery_type_id" name="delivery_type_id" class="form-control custom-select">
                                        <option disabled="">Выберите:</option>
                                        @foreach ($arSettings['delivery_type'] as $arDeliveryTypeSetting)
                                            <option data-cost="{{ $arDeliveryTypeSetting->cost }}" value="{{ $arDeliveryTypeSetting->id }}" {{ $arDeliveryTypeSetting->id == old('delivery_type_id', $arTildaOrderSettings['delivery_type']) ? ' selected' : '' }}>{{ $arDeliveryTypeSetting->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('delivery_type_id')
                                    <div id="delivery_type_id_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Адрес доставки</label>
                                    <textarea id="delivery_addr" class="form-control @error('delivery_addr') is-invalid @enderror" rows="3" name="delivery_addr" placeholder="Адрес доставки ..." required="required">{{ old('delivery_addr', $arTildaOrderSettings['delivery_addr']) }}</textarea>
                                    @error('delivery_addr')
                                    <div id="delivery_addr_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @if (!empty($arTildaOrderSettings['id']))
                <input type="hidden" name="subscribe_tilda_id" value="{{ $arTildaOrderSettings['id'] }}"/>
                @endif
                <input id="main_form_submit" type="submit" class="d-none">
            </form>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeForm.js') }}"></script>
    <script type="text/javascript">
        // Автоматически пересчитываем сумму подписки при загрузке страницы
        $().ready(function(){
            if (parseInt($('#sum_calc_type').val(),10) === 0) {
                let sum = parseInt($('#subscribe_type_id').find(':selected').data('cost'),10)
                    + parseInt($('#delivery_type_id').find(':selected').data('cost'),10)
                    + parseInt($('#pref_acc_id').find(':selected').data('cost'),10);
                $('#sum').val(sum).prop('readonly',true);
            }
            if ($('#status').val() === 'Активна') {
                $('#next_send_month').prop('required',true).prop('disabled',false);
            } else {
                $('#next_send_month').prop('required',false).prop('disabled',true);
            }
        });
        $('#status').change(function(){
            if ($(this).val() === 'Активна') {
                $('#next_send_month').prop('required',true).prop('disabled',false);
            } else {
                $('#next_send_month').prop('required',false).prop('disabled',true);
            }
        });
    </script>
@endsection
