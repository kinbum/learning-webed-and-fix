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
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="{{ route('elfinder.stand-alone.get') }}"></iframe>
            </div>
        </div>
        @php do_action('meta_boxes', 'main', 'elfinder.index') @endphp
    </div>
@endsection
