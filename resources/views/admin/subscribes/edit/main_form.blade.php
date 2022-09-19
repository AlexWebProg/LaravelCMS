@extends('admin.subscribes.edit.layout')

@section('subscribers_form_section')
<form id="main_form" action="{{ route('admin.subscribes.update', $subscribe->id) }}" method="post" class="pb-5">
    @csrf
    @method('patch')
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
                        <select id="status" name="status" class="form-control custom-select" disabled="disabled">
                            <option disabled="">Выберите:</option>
                            @foreach ($arSettings['status'] as $arStatusSetting)
                                <option value="{{ $arStatusSetting['value'] }}" {{ $arStatusSetting['value'] == old('status', $subscribe->status) ? ' selected' : '' }}>{{ $arStatusSetting['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subscribe_type_id">Тип подписки</label>
                        <select id="subscribe_type_id" name="subscribe_type_id" class="form-control custom-select">
                            <option disabled="">Выберите:</option>
                            @foreach ($arSettings['subscribe_type'] as $arSubscribeTypeSetting)
                                <option data-cost="{{ $arSubscribeTypeSetting->cost }}" value="{{ $arSubscribeTypeSetting->id }}" {{ $arSubscribeTypeSetting->id == old('subscribe_type_id', $subscribe->subscribe_type_id) ? ' selected' : '' }}>{{ $arSubscribeTypeSetting->value }}</option>
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
                                <option value="0" {{ 0 == old('sum_calc_type', $subscribe->sum_calc_type) ? ' selected' : '' }}>Считать автоматически</option>
                                <option value="1" {{ 1 == old('sum_calc_type', $subscribe->sum_calc_type) ? ' selected' : '' }}>Указать вручную</option>
                            </select>
                            <input type="text" class="form-control text-right @error('sum') is-invalid @enderror" id="sum" name="sum" value="{{ old('sum', $subscribe->subscribe_cost_str) }}" data-init-value="{{ $subscribe->subscribe_cost_str }}"/>
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
                            <option data-cost="0" value="" {{ empty(old('pref_acc_id', $subscribe->pref_acc_id)) ? ' selected' : '' }}>нет</option>
                            @foreach ($arSettings['pref_acc'] as $arPrefAccSetting)
                                <option data-cost="{{ $arPrefAccSetting->cost }}" value="{{ $arPrefAccSetting->id }}" {{ $arPrefAccSetting->id == old('pref_acc_id', $subscribe->pref_acc_id) ? ' selected' : '' }}>{{ $arPrefAccSetting->value }}</option>
                            @endforeach
                        </select>
                        @error('pref_acc_id')
                        <div id="pref_acc_id_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Исключить</label>
                        <textarea class="form-control @error('exclude') is-invalid @enderror" rows="3" name="exclude" placeholder="Исключить ...">{{ old('exclude', $subscribe->exclude) }}</textarea>
                        @error('exclude')
                        <div id="exclude_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Наш комментарий</label>
                        <textarea class="form-control @error('comment_manager') is-invalid @enderror" rows="3" name="comment_manager" placeholder="Наш комментарий ...">{{ old('comment_manager', $subscribe->comment_manager) }}</textarea>
                        @error('comment_manager')
                        <div id="comment_manager_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Комментарий подписчика</label>
                        <textarea class="form-control @error('comment_subscriber') is-invalid @enderror" rows="3" name="comment_subscriber" placeholder="Комментарий подписчика ...">{{ old('comment_subscriber', $subscribe->comment_subscriber) }}</textarea>
                        @error('comment_subscriber')
                        <div id="comment_subscriber_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subscribe_date">Дата подписки</label>
                        <input type="text" class="form-control datetimepicker-input @error('subscribe_date') is-invalid @enderror" id="subscribe_date" data-toggle="datetimepicker" data-target="#subscribe_date" name="subscribe_date" value="{{ old('subscribe_date', $subscribe->subscribe_date) }}" placeholder="Дата подписки ..."/>
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
                                    <option value="{{ $arSizeTopSetting->id }}" {{ $arSizeTopSetting->id == old('size_top_id', $subscribe->size_top_id) ? ' selected' : '' }}>{{ $arSizeTopSetting->value }}</option>
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
                                    <option value="{{ $arSizeBottomSetting->id }}" {{ $arSizeBottomSetting->id == old('size_bottom_id', $subscribe->size_bottom_id) ? ' selected' : '' }}>{{ $arSizeBottomSetting->value }}</option>
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
                                    <option value="{{ $arSizeHeightSetting->id }}" {{ $arSizeHeightSetting->id == old('size_height_id', $subscribe->size_height_id) ? ' selected' : '' }}>{{ $arSizeHeightSetting->value }}</option>
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
                                    <option value="{{ $arSizeFootSetting->id }}" {{ $arSizeFootSetting->id == old('size_foot_id', $subscribe->size_foot_id) ? ' selected' : '' }}>{{ $arSizeFootSetting->value }}</option>
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
                                <option value="{{ $arPeriodicitySetting->id }}" {{ $arPeriodicitySetting->id == old('periodicity_id', $subscribe->periodicity_id) ? ' selected' : '' }}>{{ $arPeriodicitySetting->value }}</option>
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
                                <option value="{{ $arSendDatesSetting->id }}" {{ $arSendDatesSetting->id == old('send_dates_id', $subscribe->send_dates_id) ? ' selected' : '' }}>{{ $arSendDatesSetting->value }}</option>
                            @endforeach
                        </select>
                        @error('send_dates_id')
                        <div id="send_dates_id_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="next_send_month">Следующая отправка</label>
                        <input type="text" class="form-control datetimepicker-input @error('next_send_month') is-invalid @enderror" id="next_send_month" data-toggle="datetimepicker" data-target="#next_send_month" name="next_send_month" value="{{ old('next_send_month', $subscribe->next_send_month) }}" placeholder="Следующая отправка ..."  @if ($subscribe->status === 'Активна') required="required" @else disabled="disabled" @endif />
                        @error('next_send_month')
                        <div id="next_send_month_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="delivery_type_id">Тип доставки</label>
                        <select id="delivery_type_id" name="delivery_type_id" class="form-control custom-select">
                            <option disabled="">Выберите:</option>
                            @foreach ($arSettings['delivery_type'] as $arDeliveryTypeSetting)
                                <option data-cost="{{ $arDeliveryTypeSetting->cost }}" value="{{ $arDeliveryTypeSetting->id }}" {{ $arDeliveryTypeSetting->id == old('delivery_type_id', $subscribe->delivery_type_id) ? ' selected' : '' }}>{{ $arDeliveryTypeSetting->value }}</option>
                            @endforeach
                        </select>
                        @error('delivery_type_id')
                        <div id="delivery_type_id_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Адрес доставки</label>
                        <textarea id="delivery_addr" class="form-control @error('delivery_addr') is-invalid @enderror" rows="3" name="delivery_addr" placeholder="Адрес доставки ...">{{ old('delivery_addr', $subscribe->delivery_addr) }}</textarea>
                        @error('delivery_addr')
                        <div id="delivery_addr_error" class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Планируемые отправки</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
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
    <input id="main_form_submit" type="submit" class="d-none">
</form>
@endsection
