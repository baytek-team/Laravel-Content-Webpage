@extends('Pretzel::admin')
@section('content')
<div class="webpage" style="padding-top: 100px">
	<div class="ui text aligned center header">
		<h1 style="font-size: 48px;margin-bottom: 100px">
			{{ $webpage->title }}
		</h1>
	</div>

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
	    			dump($webpage_settings);
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