@extends('admin.layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Новый товар</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribeProducts.index') }}">Товары</a></li>
                        <li class="breadcrumb-item active">Новый товар</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <form id="main_form" action="{{ route('admin.subscribeProducts.create') }}" method="post" class="pb-5">
                @csrf
                @method('put')
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
                                <div class="form-group">
                                    <label for="name">Название</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Название товара ..." required="required"/>
                                    @error('name')
                                    <div id="name_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="article">Артикул</label>
                                    <input type="text" class="form-control @error('article') is-invalid @enderror" id="article" name="article" value="{{ old('article') }}" placeholder="Артикул товара ..." required="required"/>
                                    @error('article')
                                    <div id="article_error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <input id="main_form_submit" type="submit" class="d-none">
            </form>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
