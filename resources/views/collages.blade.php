@extends('layouts.app')

<style>
    #collage-create {
        line-height: 15.333333em;
    }

    #collage-create a {
        color: #ebebeb;
        text-decoration: none !important;
    }
</style>

@section('content')
<div class="container">
    <div class="row">
        <?php /** @var \App\Models\Collage $collage */ ?>
        @foreach($collages ?? [] as $collage)
            <div class="col-md-4 collage-index-item">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $collage->title }}</h5>
                        <p class="card-text"><em>{{ __('Created') }} {{ $collage->created_at }}</em></p>
                        <a class="btn btn-primary" href="/collages/{{ $collage->id }}">{{ __('Open') }}</a>
                        <a class="btn btn-primary" href="/collages/{{ $collage->id }}/images">{{ __('Images') }} ({{ count($collage->images) }})</a>
                        <a class="btn btn-primary" href="/collages/{{ $collage->id }}/edit">{{ __('Edit') }}</a>
                        <a class="btn btn-danger" href="/collages/{{ $collage->id }}/delete" data-confirm-msg="{{ __('Delete this collage? This action cannot be undone.') }}">{{ __('Delete') }}</a>
                        <hr>
                        <p class="card-text">
                            {{ __('Upload link') }} <input class="form-control" style="text-align: center" readonly value="{{ $collage->short_url }}" id="collage-short-url">
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-4 collage-index-item">
            <div class="card">
                <div class="card-body text-center" id="collage-create">
                    <a href="/collages/create">{{ __('Create new Collage') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
