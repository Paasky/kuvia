<?php /** @var \App\Models\Collage $collage */ ?>

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-lg">
            <h1>{{ __('Upload image to') }} {{ $collage->title }}</h1>
            <p><em>{{ __('Created by') }} {{ $collage->user->name }}</em></p>
            <form action="/u/{{ $collage->key }}" method="POST" enctype="multipart/form-data">
                @csrf @honeypot
                <input class="form-control" type="file" id="image" name="image" value="{{ __('Take photo or select image') }}">
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="submit" class="btn btn-primary" value="{{ __('Upload to Collage') }}">
            </form>
        </div>
    </div>
</div>
@endsection
