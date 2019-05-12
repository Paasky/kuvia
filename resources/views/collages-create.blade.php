@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-lg">
            <h1>{{ __('Create Collage') }}</h1>
            <form action="{{ action('CollageController@create') }}" method="post">
                @csrf
                <input class="form-control" id="collage-title" name="title" placeholder="{{ __('Title') }}">
                <input type="submit" class="btn btn-primary" value="{{ __('Create') }}">
            </form>
        </div>
    </div>
</div>
@endsection
