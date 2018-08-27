<header class="header__sticky">
    <div class="header-navbar d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <ul>
                        <li class="font-weight-bold">
                            <a href="{{ route('language.change', ['lang' => 'en']) }}" class="change-lang">
                                <img src="{{ asset('bower_components/sufee-admin-dashboard/images/flags/4x3/us.svg') }}" alt="">
                            </a>
                            <a href="{{ route('language.change', ['lang' => 'vi']) }}" class="change-lang">
                                <img src="{{ asset('bower_components/sufee-admin-dashboard/images/flags/4x3/vn.svg') }}" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-8 offset-lg-4 text-right">
                    <ul>
                        @if(!Auth::check())
                            <li class="font-weight-bold"><a href="{{ route('register') }}"> @lang('auth.register') </a></li>
                            <li class="font-weight-bold"><a href="{{ route('login') }}"> @lang('auth.login') </a></li>
                        @else
                        <li class="mr-auto"><a href="{{ route('client.notifications.index') }}"><i class="fas fa-bell"></i> @lang('client.Notifications') ({{ Auth::user()->getNumberNotify() }})</a></li>
                        <div class="dropdown d-inline">
                            <li class="font-weight-bold dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <a href="#"> {{ Auth::user()->name }} </a>
                            </li>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                              <a href="{{ route('client.user.profile') }}" class="dropdown-item">{{ __('Profile') }}</a>
                              <a href="{{ route('client.orders') }}" class="dropdown-item">{{ __('Orders') }}</a>
                              <a href="{{ route('client.myproduct.index') }}" class="dropdown-item">{{ __('client.products.posted') }}</a>
                              <a href="{{ route('client.purchases.index') }}" class="dropdown-item">{{ __('client.purchases') }}</a>
                              <button class="dropdown-item"
                                onclick="javascript:document.getElementById('logout').submit()"> @lang('auth.logout')</button>
                            </div>
                        </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div> 
    {!! Form::open([
        'id' => 'logout',
        'method' => 'POST',
        'url' => route('logout'),
    ]) !!}
    {!! Form::close() !!}
    <div class="header-search">
        <div class="container">
            <div class="row">
                <div class="col-md-2 text-center">
                    <a href="{{ route('index') }}"><img src="{{ $site_info['logo'] }}" alt="" class="logo"></a>
                </div>
                <div class="col-md-9">

                    {!! Form::open([
                        'method' => 'GET',
                        'class' => 'search',
                        'url' => route('client.products.search'),
                    ]) 
                    !!}

                    {!! Form::text('search', old('search'), [
                        'placeholder' => __('client.search'),
                        'id' => 'header-search',
                        'data-url' => route('client.products.search_results'),
                        'autocomplete' => 'off'
                    ]) !!}
                    {!! Form::button(__('client.search.submit'), 
                                    [
                                        'class' => 'search-submit', 
                                        'type' => 'submit'
                                    ])
                    !!}
                    
                    {!! Form::close() !!}
                    <div id="result" class="col-sm-5 position-fixed"></div>
                </div>
                <div class="col-md-1 cart">
                    @auth
                        <a href="{{ route('client.product.new') }}" class="text-white mt-2 d-inline-block new-product"><i class="fas fa-plus-square"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
