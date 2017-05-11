@extends('Content::admin')

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

<div class="flex-center position-ref full-height">
    <div class="content">
        <form action="{{route('webpage.store')}}" method="POST" class="ui form">
            {{ csrf_field() }}

            @include('Webpage::form')

            <div class="field actions">
	            <a class="ui button" href="{{ route('webpage.index') }}">Cancel</a>
	            <button type="submit" class="ui right floated primary button">
	            	Create Content
            	</button>
            </div>
        </form>
    </div>
</div>

@endsection