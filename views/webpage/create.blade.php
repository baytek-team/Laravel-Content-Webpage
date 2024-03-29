@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="globe icon"></i>
        <div class="content">
            Webpage Management
            <div class="sub header">Create a webpage.</div>
        </div>
    </h1>
@endsection

@section('content')

    <form action="{{route('webpage.store')}}" method="POST" class="ui form">
        {{ csrf_field() }}

        @include('webpages::webpage.form')

        <div class="field actions">
            <a class="ui button" href="{{ $parent ? route('webpage.show', $parent) : route('webpage.index') }}">Cancel</a>
            <button type="submit" class="ui right floated primary button">
            	Create Webpage
        	</button>
        </div>
    </form>

@endsection