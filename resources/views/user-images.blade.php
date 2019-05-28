@extends('layouts.app')
<?php /** @var \App\User $user */ ?>

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-lg-12">
            <h1>{{ __('My Uploads') }}</h1>
            <div class="row">
                @if(count($user->images) == 0)
                    <p class="text-center">No images</p>
                @endif
                @foreach($user->images as $image)
                    <div class="col-sm-4">
                        <div class="admin-image" style="background-image: url({{$image->link}}">
                            <div class="image-actions" id="image-{{ $image->id }}">
                                <a href="/images/{{ $image->id }}/delete" class="btn btn-danger" data-confirm-msg="{{ __('Delete this image? This action cannot be undone.') }}">{{ __('Delete') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
