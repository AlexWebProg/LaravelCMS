@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Заявка {{ $subRequest->id }} {{ ($subRequest->status == 3) ? '(закрыта)' : '' }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subRequests.index', $status) }}">{{ $srBreadCrumbName }}</a></li>
                        <li class="breadcrumb-item active">Заявка {{ $subRequest->id }}</li>
                    </ol>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-12 col-lg">
                                    <dl>
                                        <dt>Подписчик</dt>
                                        <dd>{{ $subRequest->subscribe_info['user_name'] }}, {{ phoneMask($subRequest->subscribe_info['user_phone']) }}, {{ $subRequest->subscribe_info['user_email'] }}</dd>

                                        <dt>Подписка</dt>
                                        <dd>
                                            {{ $subRequest->subscribe_info['subscribe_name'] }} ( {{ $subRequest->subscribe_info['send_date'] }}, {{ $subRequest->subscribe_info['sum'] }} )
                                            @if (!empty($subRequest->subscribe_info['subscribe_id']))
                                                <a href="{{ route('admin.subscribes.edit', $subRequest->subscribe_info['subscribe_id']) }}"><i class="fa fa-external-link ml-2" aria-hidden="true"></i></a>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-12 col-lg-auto">
                                    <dl>
                                        <dt>Создана</dt>
                                        <dd>{{ $subRequest->created_text }}</dd>

                                        <dt>Обновлена</dt>
                                        <dd>{{ $subRequest->updated_text }}</dd>
                                    </dl>
                                </div>
                            </div>

                            <dl>
                                @if (!empty($subRequest->actions))
                                <dt>Что сделать</dt>
                                <dd>{{ $subRequest->actions }}</dd>
                                @endif

                                @if (!empty($subRequest->comment))
                                    <dt>Комментарий</dt>
                                    <dd>{{ $subRequest->comment }}</dd>
                                @endif
                            </dl>
                        </div>

                        @if (!empty($arComments) and count($arComments))
                            <div class="col-12">
                                <div class="row">
                                @foreach ($arComments as $arComment)
                                    <div class="card col-9 pl-0 pr-0 mt-3 {{ (!empty($arComment->bbadmin_user_id)) ? 'card-primary offset-3' : 'card-secondary' }}">
                                        <div class="card-header card-title clearfix">
                                            <div class="pull-left">{{ $arComment->name }}</div>
                                            <div class="pull-right">{{ $arComment->created }}</div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            {{ $arComment->text }}
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($subRequest->status != 3)
                            <form action="{{ route('admin.subRequests.addComment', $subRequest->id) }}" method="post" class="mt-3 pb-3">
                            @csrf
                                <div class="form-group">
                                    <label for="comment_text">Ваше сообщение</label>
                                    <textarea id="comment_text" class="form-control" name="text" rows="3" placeholder="Ваше сообщение"></textarea>
                                </div>
                                <div class="text-right">
                                    <input type="submit" class="btn btn-primary" value="Сохранить">
                                </div>
                            </form>

                            <hr/>
                            <form action="{{ route('admin.subRequests.close', $subRequest->id) }}" method="post" class="pb-5">
                                @csrf
                                @method('patch')
                                <input type="submit" class="btn btn-info" value="Закрыть заявку">
                            </form>
                        @else
                            <div class="pb-5"></div>
                        @endif

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
            <div id="page_bottom"></div>
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection
