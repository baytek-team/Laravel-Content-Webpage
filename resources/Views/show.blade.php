@extends('Content::admin')
@section('content')
<div class="webpage" style="background: {{ config('cms.content.webpage.background') }}">
	<h1 style="font-size: 48px;">
		{{ $webpage->title }}
	</h1>
	<div class="ui hidden divider"></div>
	<div class="ui hidden divider"></div>

	{!! $webpage->content !!}

	<div class="ui hidden divider"></div>
	<div class="ui hidden divider"></div>

	<div class="ui horizontal segments">
		<div class="ui segments segment">
		    <div class="ui segment header">
		        Settings
		    </div>
	        <div class="ui segment blue bottom">
	            @php
	    			dump(config('cms.content.webpage'));
	    		@endphp
	        </div>

	    </div>
	    <div class="ui segments segment">
		    <div class="ui segment header">
		        Meta Data
		    </div>

		    <div class="ui segment orange bottom">
		        @php
					dump($webpage->meta);
				@endphp
		    </div>
		</div>

	    <div class="ui segments segment">
		    <div class="ui segment header">
		        Revisions
		    </div>

		    <div class="ui segment green bottom">
		        @php
					dump($webpage->revisions);
				@endphp
		    </div>
		</div>
	</div>
</div>

@endsection