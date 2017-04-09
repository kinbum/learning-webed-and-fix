<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>{{ $pageTitle or 'Dashboard' }} - Ace Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Admin dashboard - Ace panel" name="description"/>
    <base href="/ace-panel">
    {!! Assets::renderStylesheets() !!}
    @php do_action('header_css') @endphp

    <!--<link rel="stylesheet" href="/admin/theme/lte/css/AdminLTE.min.css">-->
    <!--<link rel="stylesheet" href="/admin/theme/lte/css/skins/_all-skins.min.css">-->
    <!--<link rel="stylesheet" href="/admin/css/style.css">-->

    @stack('css-include')
    @yield('style')
    <style>
        .modal-open {
            overflow-y: hidden !important;
        }
        .img-rectangle {
            border-radius: 2px;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }
    </style>
    <link rel="shortcut icon" href="{{ get_settings('favicon') }}"/>
    <script type="text/javascript">
        var BASE_URL = '{{ asset('') }}',
            FILE_MANAGER_URL = '{{ route('elfinder.popup.get') }}';
    </script>
    {!! Assets::renderScripts('top') !!}
    @php do_action('header_js') @endphp
</head>

<body class="{{ $bodyClass or '' }} skin-purple sidebar-mini on-loading">

    <!-- Loading state -->
    <div class="page-spinner-bar">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <!-- Loading state -->

    <div class="wrapper">
        {{--BEGIN Header--}}
        @include('base::admin._partials.header')
        {{--END Header--}}

        {{--BEGIN Sidebar--}}
        @include('base::admin._partials.sidebar')
        {{--END Sidebar--}}

        <div class="content-wrapper">
            <section class="content-header">
                {{--BEGIN Page title--}}
                @include('base::admin._partials.page-title')
                {{--END Page title--}}
                {{--BEGIN Breadcrumbs--}}
                @include('base::admin._partials.breadcrumbs')
                {{--END Breadcrumbs--}}
            </section>

            <section class="content">
                {{--BEGIN Flash messages--}}
                @include('base::admin._partials.flash-messages')
                {{--END Flash messages--}}

                {{--BEGIN Content--}}
                @yield('content')
                {{--END Content--}}
            </section>
        </div>

        {{--BEGIN Footer--}}
        @include('base::admin._partials.footer')
        {{--END Footer--}}

        {{--BEGIN control sidebar--}}
        @include('base::admin._partials.control-sidebar')
        {{--END control sidebar--}}
    </div>

    {{--Modals--}}
    @include('base::admin._partials.modals')

    <!--[if lt IE 9]>
    <script src="{{ asset('admin/plugins/respond.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/excanvas.min.js') }}"></script>
    <![endif]-->

{{--BEGIN plugins--}}
<!--<script src="/admin/theme/lte/js/app.js"></script>-->
<!--<script src="/admin/js/webed-core.js"></script>-->
<!--<script src="/admin/theme/lte/js/demo.js"></script>-->
<!--<script src="/admin/js/script.js"></script>-->
{!! \Assets::renderScripts('bottom') !!}
{{--END plugins--}}
    @php do_action('footer_js') @endphp

    @stack('js-include')
    @yield('script')
    
</body>
</html>
