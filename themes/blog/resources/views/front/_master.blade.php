<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <title>{{ $pageTitle or '' }} {{ get_settings('site_title', 'Dev dỉary') ?: 'Dev dỉary' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! seo()->render() !!}
    <link href="https://fonts.googleapis.com/css?family=VT323" rel="stylesheet">
    <base href="{{ asset('/') }}">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    @php do_action('front_header_css') @endphp
    @yield('css')
    <style>
        body {
            font-family: monospace;
            color: #000;
            background-color: #F5F5F5;
            font-size: 16px;
            line-height: 1.5;
            -ms-overflow-x: hidden;
            overflow-x: hidden;
        }
        .wrapper {
            width: 776px;
            margin: 0 auto;
        }
        a {
            text-decoration: none;
            color: #000;
        }
        header .top {
            text-align: center;
        }
        header .top>h1 {
            font-family: 'VT323', monospace;
            font-size: 3.5rem;
            font-weight: normal;
            margin: 0;
        }
        p.slogan {
            margin: 5px;
        }
        p.slogan>small {
            font-size: 0.625rem;
            font-weight: normal;
            font-family: monospace;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #A1A1A1;
            font-style: italic;
        }
        header .navigation {
            padding: 40px 0;
        }
        ul.nav {
            list-style: none;
            text-align: center;
        }
        ul.nav>li {
            display: inline-block;
            font-size: 1.15rem;
            margin-right: 30px;
            font-weight: bold;
        }
        ul.nav>li>a {
            color: #000;
        }
        article.article {
            background-color: #FFFFFF;
            border: 0px solid rgba(255,255,255,0.25);
            border-radius: 2px;
            box-shadow: 0 1px 5px gainsboro;
            color: #2A2A2A;
            position: relative;
            transition: opacity 0.2s ease-in-out;
        }
        article .article__thumbnail {
            text-align: center;
        }
        article.article img {
            max-width: 100%;
        }
        article.article .article__content {
            white-space: normal!important;
            margin-bottom: 1.25rem;
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            font-size: 1rem;
        }
        article.article .article__content-body {
            margin-top: 40px;
        }
        span.label>span.avatar {
            background-size: 24px 24px;
            border-radius: 13px;
            box-shadow: inset 0 0 1px 1px rgba(0, 0, 0, .15);
            float: left;
            height: 24px;
            margin-right: 5px;
            width: 24px;
        }
    </style>
    @php do_action('front_header_js') @endphp
</head>
<body class="{{ $bodyClass or '' }} @php do_action('front_body_class') @endphp">
<div class="wrapper" id="wrapper">
    @php do_action('front_before_header_wrapper_content') @endphp
    <header class="header" id="header">
        @include('theme::front._partials.header')
    </header>
    @include('theme::front._partials.flash-messages')
    @php do_action('front_before_main_wrapper_content') @endphp
    <main class="main" id="main">
        <div class="blog-listing__article">
            @yield('content')
        </div>
    </main>
    @php do_action('front_before_footer_wrapper_content') @endphp
    <footer class="footer" id="footer">
        @include('theme::front._partials.footer')
    </footer>
    @php do_action('front_bottom_wrapper_content') @endphp
</div>
@php do_action('front_bottom_content') @endphp
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>-->
@php do_action('front_footer_js') @endphp
@stack('jsInclude')
@yield('script')
</body>
</html>
