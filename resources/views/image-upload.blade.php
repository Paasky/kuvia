<?php /** @var \App\Models\Collage $collage */ ?>

@extends('layouts.app')

@section('content')
    <style>
    #image {
        display: none;
    }
    #image-upload-holder {
        height: 70vh;
        width: 70vh;
        line-height: 70vh;
        max-width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    #image-upload-preview {
        display: inline-block;
        background-color: #333;
        background-position: center;
        background-size: contain;
        background-repeat: no-repeat;
        width: 100%;
        height: 100%;
        text-shadow: 1px 1px 3px #000;
    }
    </style>
    <script src="{{ asset('js/image-upload.js') . '?v='.filemtime(public_path('js/image-upload.js')) }}"></script>

    <div class="container">
    <div class="row justify-content-center">
        <div class="col col-lg col-lg-8">
            <h3>{{ __('Upload image to') }} {{ $collage->title }}</h3>
            <form action="/u/{{ $collage->key }}" method="POST" enctype="multipart/form-data">
                @csrf @honeypot
                <input class="form-control" type="file" accept="image/jpg|image/jpeg|image/png|image/gif" id="image" name="image" value="{{ __('Take photo or select image') }}">
                <div id="image-upload-holder">
                    <div id="image-upload-preview">
                        <div style="display: inline-block; line-height:2em;">
                            {{ __('Click to take photo or select image') }}
                            <br>
                            ( {{ __('jpg / png / gif, max 5MB') }} )
                        </div>
                    </div>
                </div>
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="submit" disabled class="btn btn-primary btn-lg" style="width: 70vh; max-width: 100%; margin-top: 1em;" value="{{ __('Upload to Collage') }}">
            </form>
        </div>
    </div>
</div>
@endsection
