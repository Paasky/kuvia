<?php /** @var \App\Models\Collage $collage */ ?>
<?php /** @var string $qr */ ?>
@extends('layouts.app')

<link href="{{ asset('css/collage.css') . '?v='.filemtime(public_path('css/collage.css')) }}" rel="stylesheet">

<script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
<script>
    var COLLAGE_ID = {{ $collage->id }};
</script>
<script src="{{ asset('js/collage.js') . '?v='.filemtime(public_path('js/collage.js')) }}"></script>

@section('content')
<div class="container collage-view">
    <div id="collage-view-subtext">{{ __('Add images using the QR-code or link') }}</div>
    <div id="collage-view-images"></div>
    <div id="collage-view-links">
        <div id="collage-view-short-url">
            <img id="collage-view-qr" src="{{ $qr }}" alt="QR-code">
            <div><a href="/{{ $collage->short_url }}" target="_blank">{{ str_replace(['http://', 'https://', "pekko-dev."], '', url($collage->short_url)) }}</a> </div>
        </div>
    </div>
</div>
@endsection
