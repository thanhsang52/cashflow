<div id="switcher">
    <div class="switcher box-color dark-white text-color" id="sw-theme">
        <a ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
            <i class="fa fa-gear"></i>
        </a>
        <div class="box-header">
            <h2>{{ __('backend.themeSwitcher') }}</h2>
        </div>
        <div class="box-body p-t-xs ">
            <!-- <p class="m-b-sm">{{ __('backend.themes') }}</p>
            <div data-target="bg" class="text-u-c text-center _600 clearfix">
                <label class="p-a col-xs-6 light pointer m-a-0">
                    <input type="radio" name="theme" value="" hidden>
                    {{ __('backend.themes1') }}
                </label>
                <label class="p-a col-xs-6 grey pointer m-a-0">
                    <input type="radio" name="theme" value="grey" hidden>
                    {{ __('backend.themes2') }}
                </label>
                <label class="p-a col-xs-6 dark pointer m-a-0">
                    <input type="radio" name="theme" value="dark" hidden>
                    {{ __('backend.themes3') }}
                </label>
                <label class="p-a col-xs-6 black pointer m-a-0">
                    <input type="radio" name="theme" value="black" hidden>
                    {{ __('backend.themes4') }}
                </label>
            </div>
            <br> -->
            <p class="m-b-sm">{{ __('inventory::pos.terminal_register') }}</p>
            <div data-target="bg" class="text-u-c text-center _600 clearfix">
                <a id="short-cut" class="btn light btn-block m-b-xs text-left p-x-1" data-toggle="modal" data-target="#open-terminal-register" title="inventory::pos.open_terminal_register" data-hs-unfold-invoker="">
                    <i class="tio-folder-add"></i>
                    {{__('inventory::pos.open_terminal_register')}}
                </a>
                <a id="short-cut" class="btn light btn-block m-b-xs text-left p-x-1"
                    data-toggle="modal" data-target="#close-terminal-register" title="{{__('inventory::pos.close_terminal_register')}}">
                    <i class="tio-folder"></i>
                    {{__('inventory::pos.close_terminal_register')}}
                </a>
            </div>
            @if(count(Helper::languagesList()) >0)
                <p class="m-b-sm">{{ __('backend.languages') }}</p>
                <div style="max-height: 200px;overflow-y: scroll">
                @foreach(Helper::languagesList() as $ActiveLanguage)
                    <div>
                        <a href="{{ route("localeChange",$ActiveLanguage->code) }}"
                           class="btn light btn-block m-b-xs text-left p-x-1">
                            @if($ActiveLanguage->icon !="")
                                <img
                                    src="{{ asset('assets/dashboard/images/flags/'.$ActiveLanguage->icon.".svg") }}"
                                    alt="" class="w-20">
                            @endif
                            {{ $ActiveLanguage->title }}
                        </a>
                    </div>
                @endforeach
                </div>
            @endif
            <!-- <div class="m-t-2">
                <a href="{{ route('cacheClear') }}" class="btn dark btn-block"
                   onclick="return confirm('{{ __('backend.cashClearMsg') }}')"><small class="text-sm">{!!  __('backend.cashClear') !!}</small></a>

            </div> -->
        </div>
    </div>

</div>
