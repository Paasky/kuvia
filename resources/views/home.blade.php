@extends('layouts.app')

<?php $tiles = [
    [
        'title' => 'My Collages',
        'src' => 'collage-icon-alt-light.svg',
        'url' => '/collages',
    ],
    [
        'title' => 'Create Collage',
        'icon' => 'fa-plus-circle',
        'url' => '/collages',
    ],
    [
        'title' => 'My Images',
        'icon' => 'fa-images',
        'url' => '/images',
    ],
    [
        'title' => 'Options',
        'icon' => 'fa-user-cog',
        'url' => '/options',
    ],
    [
        'title' => 'Logout',
        'icon' => 'fa-sign-out-alt',
        'url' => '/logout',
    ],
]; ?>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row menu-tiles text-center">
                @foreach($tiles as $tile)
                    <div class="col-md-4">
                        <div class="menu-tile">
                            <a href="{{ $tile['url'] }}">
                                @if(isset($tile['icon']))
                                    <i class="fas {{ $tile['icon'] }}"></i>
                                @else
                                    <img src="{{ asset("img/{$tile['src']}") }}" alt="{{ $tile['title'] }}">
                                @endif
                                <p>{{ $tile['title'] }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
