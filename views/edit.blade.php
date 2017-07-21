@extends('content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="world icon"></i>
        <div class="content">
            Webpage Management
            <div class="sub header">Manage the webpage content type.</div>
        </div>
    </h1>
@endsection

@section('content')
<div id="registration" class="ui container">
    <div class="ui hidden divider"></div>
    <form action="{{ route('webpage.update', $webpage->id) }}" method="POST" class="ui form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('Webpage::form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('webpage.index') }}">Cancel</a>

            <button type="submit" class="ui right floated primary button">
                Update Content
            </button>
        </div>
    </form>
</div>

@endsection