@extends('layouts.app')

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
                        <a class="btn btn-primary" href="/collages/{{ $collage->id }}/images">{{ __('Edit') }}</a>
                        <a class="btn btn-danger" href="#">{{ __('Delete') }}</a>
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
                    <h5 class="card-title" style="opacity: 0;">.</h5>
                    <p class="card-text" style="opacity: 0;">.</p>
                    <a class="btn btn-primary" href="/collages/create">{{ __('New Collage') }}</a>
                    <hr style="opacity: 0">
                    <p class="card-text" style="opacity: 0">. <input class="form-control"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
