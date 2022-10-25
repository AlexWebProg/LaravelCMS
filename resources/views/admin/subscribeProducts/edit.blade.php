@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $subscribeProduct->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribeProducts.index') }}">Товары</a></li>
                        <li class="breadcrumb-item active">{{ $subscribeProduct->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Данные товара</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="main_form" action="{{ route('admin.subscribeProducts.update', $subscribeProduct->id) }}" method="post" class="pb-5">
                                @csrf
                                @method('patch')
                                <div class="form-group">
                                    <label for="name">Название</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $subscribeProduct->name) }}" placeholder="Название товара ..." required="required"/>
                                    @error('name')
                                    <div id="name_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="article">Артикул</label>
                                    <input type="text" class="form-control @error('article') is-invalid @enderror" id="article" name="article" value="{{ old('article', $subscribeProduct->article) }}" placeholder="Артикул товара ..." required="required"/>
                                    @error('article')
                                    <div id="article_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <input id="main_form_submit" type="submit" class="d-none">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Удаление</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('admin.subscribeProducts.delete', $subscribeProduct->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger pull-right confirm_subscribe_product_delete"><i class="fa fa-trash mr-2"></i> Удалить товар</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/subscribeProducts.js') }}"></script>
@endsection
