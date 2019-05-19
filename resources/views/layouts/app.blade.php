<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.4.3.1.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('css/bootstrap.4.3.1.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.5.8.2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') . '?v='.filemtime(public_path('css/custom.css')) }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="menu-tiles">
            <?php $tiles = [
                [
                    'id' => 'menu-toggle',
                    'title' => 'Menu',
                    'icon' => 'fa-chevron-down',
                    'url' => '#',
                ],
                [
                    'id' => 'menu-collages',
                    'title' => 'My Collages',
                    'src' => 'collage-icon-alt-light.svg',
                    'url' => '/collages',
                ],
                [
                    'id' => 'menu-uploads',
                    'title' => 'My Uploads',
                    'icon' => 'fa-images',
                    'url' => '/images',
                ],
                [
                    'id' => 'menu-options',
                    'title' => 'Options',
                    'icon' => 'fa-user-cog',
                    'url' => '/options',
                ],
                [
                    'id' => 'menu-logout',
                    'title' => 'Logout',
                    'icon' => 'fa-sign-out-alt',
                    'url' => '/logout',
                ],
            ]; ?>
            @foreach($tiles as $tile)
                <div id="{{ $tile['id'] }}" class="menu-tile {{ '/'.request()->path() == $tile['url'] ? 'active' : '' }}">
                    <a href="{{ $tile['url'] }}">
                        @if(isset($tile['icon']))
                            <i class="fas {{ $tile['icon'] }}"></i>
                        @else
                            <img src="{{ asset("img/{$tile['src']}") }}" alt="{{ $tile['title'] }}">
                        @endif
                        <p>{{ $tile['title'] }}</p>
                    </a>
                </div>
            @endforeach
        </div>

        <main class="py-4">
            @if(session('success') || session('warning') || session('danger') || session('info'))
                <div class="row">
                    <div class="col col-lg col lg-12">
                        @if(session('danger'))
                            <div class="alert alert-danger">
                                {{ session('danger') }}
                            </div>
                        @endif
                        @if(session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
