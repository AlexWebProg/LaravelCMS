@extends('admin.subscribes.edit.layout')

@section('subscribers_form_section')
    <form action="{{ route('admin.subscribes.sendOne', $subscribe->id) }}" method="post" class="pb-5">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Отправка подписки</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="sending_date">Дата отправки</label>
                            <input type="text" class="form-control datetimepicker-input @error('sending_date') is-invalid @enderror" id="sending_date" data-toggle="datetimepicker" data-target="#sending_date" name="sending_date" value="{{ old('sending_date', date('d.m.Y H:i')) }}" placeholder="Дата отправки ..." required="required"/>
                            @error('sending_date')
                            <div id="sending_date_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sender">Отправитель</label>
                            <input type="text" class="form-control @error('sender') is-invalid @enderror" id="sender" name="sender" value="{{ old('sender', auth()->user()->name) }}" placeholder="Отправитель ..." required="required"/>
                            @error('sender')
                            <div id="sender_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sent_month">Месяц, за который выполняется отправка</label>
                            <input type="text" class="form-control datetimepicker-input @error('sent_month') is-invalid @enderror" id="sent_month" data-toggle="datetimepicker" data-target="#sent_month" name="sent_month" value="{{ old('sent_month', $subscribe->next_send_month) }}" placeholder="Месяц, за который выполняется отправка ..." required="required"/>
                            @error('sent_month')
                            <div id="sent_month_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2 d-none d-sm-inline" aria-hidden="true"></i>Отправить<span class="d-none d-sm-inline"> подписку</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
