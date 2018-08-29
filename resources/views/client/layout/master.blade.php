<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', $site_info['title'])
    </title>
    <meta name="description" content="@yield('description', $site_info['description'])">
    <meta name="keyword" content="@yield('keyword', $site_info['keyword'])">
    <link rel="icon" type="image/x-icon" href="{{ $site_info['favicon'] }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    @include('client.assets.css')
    
    @yield('css')

    @yield('css')

</head>
<body>
    
    @include('client.layout.header')

    <div class="main">
        
        @yield('content')

    </div>
    
    @include('client.layout.footer')
    @auth
        @include('chat')
    @endauth
    @include('client.assets.js')   
    @include('client.notify')

    @yield('js')
    
</body>
</html>
