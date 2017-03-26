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
    <meta content="Admin dashboard - WebEd" name="description"/>

    <base href="{{ asset('') }}">

    @stack('css-include')
    @yield('style')

    <link rel="shortcut icon" href="http://dishantagnihotri.com/public/img/acemble_favicon.png"/>

    <script type="text/javascript">
        var BASE_URL = '{{ asset('') }}'
    </script>
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
        @include('base::_partials.header')
        {{--END Header--}}

        {{--BEGIN Sidebar--}}
        @include('base::_partials.sidebar')
        {{--END Sidebar--}}

        <div class="content-wrapper">
            <section class="content-header">
                {{--BEGIN Page title--}}
                @include('base::_partials.page-title')
                {{--END Page title--}}
                {{--BEGIN Breadcrumbs--}}
                @include('base::_partials.breadcrumbs')
                {{--END Breadcrumbs--}}
            </section>

            <section class="content">
                {{--BEGIN Flash messages--}}
                @include('base::_partials.flash-messages')
                {{--END Flash messages--}}

                {{--BEGIN Content--}}
                @yield('content')
                {{--END Content--}}
            </section>
        </div>

        {{--BEGIN Footer--}}
        @include('base::_partials.footer')
        {{--END Footer--}}

        {{--BEGIN control sidebar--}}
        @include('base::_partials.control-sidebar')
        {{--END control sidebar--}}
    </div>

    {{--Modals--}}
    @include('base::_partials.modals')

    <!--[if lt IE 9]>
    <script src="{{ asset('admin/plugins/respond.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/excanvas.min.js') }}"></script>
    <![endif]-->

    {{--BEGIN plugins--}}
    {{--END plugins--}}


    @stack('js-include')
    @yield('script')

</body>
</html>
