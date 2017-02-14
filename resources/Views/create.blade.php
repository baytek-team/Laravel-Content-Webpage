@extends('Content::admin')
@section('content')

<div class="ui two column stackable grid">
    <div class="ten wide column">
        <h1 class="ui header">
            <i class="browser icon"></i>
            <div class="content">
                Webpage Management
                <div class="sub header">Create a webpage.</div>
            </div>
        </h1>
    </div>
</div>

<div class="ui hidden divider"></div>

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