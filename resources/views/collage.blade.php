<?php /** @var \App\Models\Collage $collage */ ?>
<?php /** @var string $qr */ ?>
@extends('layouts.app')
<style>
    html, body {
        background-color: #222 !important;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
    }
    .navbar {
        opacity: 0.04;
        position: absolute !important;
        width: 100%;
        background-color: #222 !important;
        z-inex:1000;
    }

    .navbar:hover {
        opacity: 1;
    }

    .navbar * {
        color: white !important;
    }

    main.py-4 {
        padding: 0 !important;
        margin: 0 !important;
    }

    .container.collage-view {
        max-width: 100vw;
        padding: 0;
        margin: 0;
        max-height: 100vh;
    }

    .collage-view {
        color: darkorange;
    }

    #collage-view-subtext {
        position: absolute;
        top: 46vh;
        width: 100%;
        text-align: center;
        font-size: 8vh;
        color: #444;
    }

    #collage-view-images {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
    }

    #collage-view-links {
        position: absolute;
        right: 0;
        bottom: 0;
        padding: 0;
        opacity: 0.75;
    }

    #collage-view-links, #collage-view-links * {
        background-color: #fff !important;
    }

    #collage-view-short-url {
         color: #555;
    }

    #collage-view-short-url div {
        color: #000 !important;
        margin: -1em 0.5em 0.5em;
        text-align: center;
    }

    #collage-view-short-url div a {
        color: inherit !important;
        text-decoration: inherit !important;
    }

    #collage-view-qr {
        width: 100%;
    }

    .collage-image {
        background-size: cover;
        background-position: center center;
        display: inline-block;
    }

    .collage-image-portrait {
        width:  25vw;
        height: 33.3333333vw;
    }

    .collage-image-landscape {
        width:  25vw;
        height: 16.6666666vw;
    }
</style>
@section('content')
<div class="container collage-view">
    <div id="collage-view-subtext">{{ __('Add images using the QR-code or link') }}</div>
    <div id="collage-view-images">
        @foreach($collage->images as $image)
            <div class="collage-image collage-image-{{ $image->type }}" style="background-image: url({{ $image->link }})"></div>
        @endforeach
    </div>
    <div id="collage-view-links">
        <div id="collage-view-short-url">
            <img id="collage-view-qr" src="{{ $qr }}" alt="QR-code">
            <div><a href="/{{ $collage->short_url }}" target="_blank">{{ str_replace(['http://', 'https://', "pekko-dev."], '', url($collage->short_url)) }}</a> </div>
        </div>
    </div>
</div>
@endsection
