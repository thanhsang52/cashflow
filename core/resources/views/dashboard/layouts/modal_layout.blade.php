<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    @include('dashboard.layouts.head')
    @include('dashboard.layouts.others.languages')
</head>
<body>

<div class="app" id="app">


    <div id="content" class="app-content box-shadow-z0" role="main">
 
        <div ui-view class="app-body" id="view">

            @yield('content')
        </div>
    </div>


</div>
<!-- Custom JS -->
@yield('js-script')
@include('dashboard.layouts.foot')
</body>
</html>
