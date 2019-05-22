@extends('layouts.app')
<?php /** @var \App\Models\Collage $collage */ ?>
<script src="{{ asset('js/collage-admin.js') . '?v='.filemtime(public_path('js/collage-admin.js')) }}"></script>
@section('content')
<div class="container">
    <div class="row">
        <div class="col col-lg-12">
            <h1>{{ $collage->title }}</h1>
            <div class="row">
                @if(count($collage->images) == 0)
                    <p class="text-center">No images</p>
                @endif
                @foreach($collage->images as $image)
                    <div class="col-sm-4">
                        <div class="collage-admin-image" style="background-image: url({{$image->link}}">
                            <div class="collage-admin-image-actions" id="collage-admin-img-{{ $image->idcolla }}">
                                <a href="/images/{{ $image->id }}/delete" class="btn btn-danger" data-confirm-msg="{{ __('Delete this image? This action cannot be undone.') }}">{{ __('Delete') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="btn btn-lg btn-primary" href="/u/{{ $collage->key }}">{{ __('Upload image') }}</a>
        </div>
    </div>
</div>
@endsection
