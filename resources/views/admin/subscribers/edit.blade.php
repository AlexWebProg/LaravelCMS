@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $subscriber->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribers.index') }}">Подписчики</a></li>
                        <li class="breadcrumb-item active">{{ $subscriber->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <form id="main_form" action="{{ route('admin.subscribers.update', $subscriber->user_id) }}" method="post" class="pb-5">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Данные подписчика</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Имя</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $subscriber->name) }}" placeholder="Имя подписчика ..." required="required"/>
                                    @error('name')
                                    <div id="name_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Мейл</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $subscriber->email) }}" placeholder="Мейл подписчика ..." required="required"/>
                                    @error('email')
                                    <div id="email_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Телефон</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $subscriber->phone) }}" placeholder="Телефон подписчика ..." required="required"/>
                                    @error('phone')
                                    <div id="phone_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Подписки подписчика</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (!empty($arSubscribes) && count($arSubscribes))
                                    <ul>
                                        @foreach($arSubscribes as $subscribe)
                                            <li><a href="{{ route('admin.subscribes.edit', $subscribe->id) }}">{{ $subscribe->subscribeTypeSetting->value }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    нет
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="{{ route('admin.subscribes.create', $subscriber->user_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus mr-2"></i> Добавить подписку
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="user_id" value="{{ $subscriber->user_id }}"/>
                <input id="main_form_submit" type="submit" class="d-none">
            </form>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
