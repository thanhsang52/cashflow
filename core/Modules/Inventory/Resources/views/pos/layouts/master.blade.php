<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    <link rel="stylesheet" href="{{URL::asset('core/Modules/Inventory/Resources/assets')}}/css/google-fonts.css">
    <link rel="stylesheet" href="{{URL::asset('core/Modules/Inventory/Resources/assets')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{URL::asset('core/Modules/Inventory/Resources/assets')}}/css/icon-set/style.css">

    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Inventory/Resources/assets/css/theme.minc619.css?v=1.0') }}">
    @include('dashboard.layouts.head')
    @include('dashboard.layouts.others.languages')
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Inventory/Resources/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Inventory/Resources/assets/css/pos.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Inventory/Resources/assets/css/toastr.css') }}">
    @if(Helper::GeneralSiteSettings("css")!="")
        <style type="text/css">
            {!! Helper::GeneralSiteSettings("css") !!}
        </style>
    @endif
</head>

<body class="footer-offset dir-{{ @Helper::currentLanguage()->direction }} lang-{{ @Helper::currentLanguage()->code }} {{ (!Helper::GeneralSiteSettings("style_change") && Helper::GeneralSiteSettings("style_type"))?"dark":"" }}">

    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d-none">
                    <div class="style-i1">
                        <img width="200" src="{{ URL::asset('core/Modules/Inventory/Resources/assets/img/loader.gif')}}" alt="{{__('loader gif')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <a class="navbar-brand pt-0 pb-0" href="#" aria-label="Front">
                @if(Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) !="")
                    <img alt="" class="navbar-brand-logo w-i1"
                         src="{{ URL::to('uploads/settings/'.Helper::GeneralSiteSettings('style_logo_' . @Helper::currentLanguage()->code)) }}">
                @else
                    <img alt="" src="{{ URL::to('uploads/settings/nologo.png') }}">
                @endif
                </a>
            </div>
            <div class="navbar-nav-wrap-content-right">
            <ul class="navbar-nav align-items-center flex-row">
                    <li class="nav-item d-sm-inline-block">
                        <div class="hs-unfold">
                            <a id="short-cut" class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                data-toggle="modal" data-target="#short-cut-keys" title="{{__('inventory::pos.short_cut_keys')}}">
                                <i class="tio-keyboard"></i>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item d-sm-inline-block">
                        <div class="hs-unfold">
                            <a id="short-cut" class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                data-toggle="modal" data-target="#open-terminal-register" title="{{__('inventory::pos.open_terminal_register')}}">
                                <i class="tio-folder-add"></i>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item d-sm-inline-block">
                        <div class="hs-unfold">
                            <a id="short-cut" class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                data-toggle="modal" data-target="#close-terminal-register" title="{{__('inventory::pos.close_terminal_register')}}">
                                <i class="tio-folder"></i>
                            </a>
                           
                        </div>
                    </li>
                    <li class="nav-item d-sm-inline-block">
                        <div class="hs-unfold">
                            <a data-toggle="tooltip" class="js-hs-unfold-invoker btn btn-icon ajax-modal"
                                data-href="{{route('pos.orders','type=index')}}" data-title="{{__('inventory::pos.order_list')}}">
                                <i class="tio-shopping-basket"></i>
                            </a>
                            <!-- <div class="tooltip bs-tooltip-top" role="tooltip">
                                <div class="arrow"></div>
                                <div class="tooltip-inner"></div>
                            </div> -->
                        </div>
                    </li>
                    <li class="nav-item d-sm-inline-block">
                        <div class="hs-unfold">
                            <a onclick="toggle_full_screen(); return false" class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                 target="_blank" title="{{__('inventory::pos.full_screen')}}">
                                <i class="tio-fullscreen-1-1"></i>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href=""
                                data-hs-unfold-options='{
                                        "target": "#accountNavbarDropdown",
                                        "type": "css-animation"
                                    }'>
                                <div class="avatar avatar-sm avatar-circle">
                                    <!-- <img class="avatar-img" src="{{ URL::asset('core/Modules/Inventory/Resources/assets/img/160x160/img1.jpg')}}" alt="Image"> -->
                                    @if(Auth::user()->photo !="")
                                        <img class="avatar-img" src="{{ asset('uploads/users/'.Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                                            title="{{ Auth::user()->name }}">
                                    @else
                                        <img class="avatar-img" src="{{ asset('uploads/contacts/profile.jpg') }}" alt="{{ Auth::user()->name }}"
                                            title="{{ Auth::user()->name }}">
                                    @endif
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>
                            <div id="accountNavbarDropdown"
                                class="w-i2 hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img" src="{{ Auth::user()->photo?asset('uploads/users/'.Auth::user()->photo):asset('uploads/contacts/profile.jpg')}}" alt="Image">
                                        </div>
                                        <div class="media-body">
                                            
                                            <span class="card-title h5">{{ Auth::user()->name }}</span>
                                            <span class="card-text">{{ Auth::user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                @if(Auth::user()->permissions ==0 || Auth::user()->permissions ==1)
                                    <a class="dropdown-item"
                                    href="{{ route('usersEdit',Auth::user()->id) }}"><span>{{ __('backend.profile') }}</span></a>
                                @endif
                                <a class="dropdown-item" href="{{ route('adminLogout') }}" id="logoutLink">
                                    <span class="text-truncate pr-2" title="{{ __('backend.logout') }}">{{ __('backend.logout') }}</span>
                                </a>
                               
                            </div>
                        </div>
                    </li>
                                     
                </ul>
            </div>
            
        </div>
    </header>
    @include('inventory::pos.layouts.modal')
    <main id="content" role="main" class="main pointer-event">
        <section class="section-content pt-5">
            @yield('content')
            
        </section>

        <!-- <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div> -->
      
    </main>
    @stack('before-scripts')
    <script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/vendor.min.js')}}"></script>
    <script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/theme.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/toastr.js')}}"></script>
    <script src="https://unpkg.com/currency.js@~2.0.0/dist/currency.min.js"></script>
    <script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/pos.js')}}"></script>
    <script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/cart.js')}}"></script>
    @stack('after-scripts')
@if ($errors->any())
    <script>
        "use strict";

        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif


@stack('script_2')
@include('dashboard.layouts.foot')  
@include('inventory::pos.layouts.settings')
</body>
</html>