<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    @include('dashboard.layouts.head')
    @include('dashboard.layouts.others.languages')
</head>
<body>
@include('dashboard.layouts.modal')
<div class="app" id="app">
    @php( $webmailsNewCount= Helper::webmailsNewCount())
    @include('dashboard.layouts.menu')
    <!-- Preloader area start -->
    <div id="preloader" style="display: none;"></div>
    <!-- Preloader area end -->
    <div id="content" class="app-content box-shadow-z0" role="main">
        @include('dashboard.layouts.header')
        @include('dashboard.layouts.footer')
        <div ui-view class="app-body" id="view">
            @include('dashboard.layouts.errors')
            @yield('content')
        </div>
    </div>

    @include('dashboard.layouts.settings')
</div>
@include('dashboard.layouts.foot')
</body>
</html>
