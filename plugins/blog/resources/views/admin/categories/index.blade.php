@extends('base::admin._master')

@section('css')

@endsection

@section('js')

@endsection

@section('js-init')

@endsection

@section('content')
    <div class="layout-1columns">
        <div class="column main">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="icon-layers font-dark"></i>
                        Categories
                    </h3>
                    <div class="box-tools">
                        <a class="btn btn-transparent green btn-sm"
                           href="{{ route('blog.categories.create.get') }}">
                            <i class="fa fa-plus"></i> Create
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    {!! $dataTable or '' !!}
                </div>
            </div>
            @php do_action('meta_boxes', 'main', 'webed-blog.categories.index') @endphp
        </div>
    </div>
@endsection
