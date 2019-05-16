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
        width:  15vw;
        height: 20vw;
    }

    .collage-image-landscape {
        width:  20vw;
        height: 15vw;
    }
</style>

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

<script src="/masonry.js"></script>
<script>
    var collageId = {{ $collage->id }};
    var lastImageId = 0;
    var $grid = null;
    var masonry = null;
    var imageLoader = null;

    window.onload = function() {
        console.log('window.onload');
        $grid = $('#collage-view-images');
        imageLoader = setInterval(loadNewImages, 5000);
        loadNewImages();
    };

    function loadNewImages() {
        var url = '/collages/' + collageId + '/ui-images/after-image-id/' + lastImageId;
        console.log('Load new images: '+url);
        $.getJSON(url, function (images) {
            console.log(images);
            images.forEach(function (image) {
                if($('.collage-image-'+image.id).length) {
                    return;
                }

                var $elem = $(getImageHtml(image));
                $grid.prepend($elem);

                if(!masonry) {
                    console.log('boot masonry with img id '+image.id);
                    masonry = new Masonry('#collage-view-images', {itemSelector: '.collage-image', columnWidth: 1});
                } else {
                    console.log('prepend img id '+image.id);
                    masonry.prepended($elem[0]);
                }

                lastImageId = image.id;
            });
        });
    }

    function getImageHtml(image) {
        return '' +
            '<div ' +
            '   class="collage-image collage-image-' + image.type + ' collage-image-' + image.id + '" ' +
            '   style="background-image: url(' + image.link + ')">' +
            '</div>';
    }
</script>
