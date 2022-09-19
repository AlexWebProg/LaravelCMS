@extends('admin.subscribes.edit.layout')

@section('subscribers_form_section')
    <form action="{{ route('admin.subscribes.updateStatus', $subscribe->id) }}" method="post" class="pb-5">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ ($subscribe->status === 'Отменена') ? 'Активация' : 'Отмена' }} подписки</h3>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="action_type" value="{{ ($subscribe->status === 'Отменена') ? 'Активация' : 'Отмена' }}"/>
                        <input type="hidden" name="status" value="{{ ($subscribe->status === 'Отменена') ? 'Активна' : 'Отменена' }}"/>
                        <div class="form-group">
                            <label for="user">Выполнил</label>
                            <input type="text" class="form-control @error('user') is-invalid @enderror" id="user" name="user" value="{{ old('user', auth()->user()->name) }}" placeholder="Выполнил ..." required="required"/>
                            @error('user')
                            <div id="user_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($subscribe->status === 'Отменена')
                            <div class="form-group">
                                <label for="next_send_month">Следующая отправка</label>
                                <input type="text" class="form-control datetimepicker-input @error('next_send_month') is-invalid @enderror" id="next_send_month" data-toggle="datetimepicker" data-target="#next_send_month" name="next_send_month" value="{{ old('next_send_month', '') }}" placeholder="Следующая отправка ..."  required="required" />
                                @error('next_send_month')
                                <div id="next_send_month_error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Наш комментарий</label>
                            <textarea class="form-control @error('comment_manager') is-invalid @enderror" rows="3" name="comment_manager" placeholder="Наш комментарий ...">{{ old('comment_manager') }}</textarea>
                            @error('comment_manager')
                            <div id="comment_manager_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Комментарий подписчика</label>
                            <textarea class="form-control @error('comment_subscriber') is-invalid @enderror" rows="3" name="comment_subscriber" placeholder="Комментарий подписчика ...">{{ old('comment_subscriber') }}</textarea>
                            @error('comment_subscriber')
                            <div id="comment_subscriber_error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2 d-none d-sm-inline" aria-hidden="true"></i>Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
