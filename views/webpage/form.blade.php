@if($parents)
	<div class="field">
		<label for="title">Parent</label>
		<select name="parent_id" class="ui search dropdown">
			<option value="">No Parent</option>
			@foreach($parents as $item)

				@php
					//Reenable selection of items after its been disabled
					if ($disabledFlag && $item->depth <= $disabledDepth) {
						$disabledFlag = false;
					}

					//Prevent selection of the current folder or its children
					if ($webpage->id == $item->id) {
						$disabledFlag = true;
						$disabledDepth = $item->depth;
					}
				@endphp

				<option value="{{ $item->id }}"
					@if(isset($parent) && $parent->id == $item->id) selected="selected"@endif
					@if($disabledFlag) disabled @endif>{!! str_repeat('- ', $item->depth) !!}{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
@else
	@if($webpage->id)
		@section('page.head.menu')
		    <div class="ui secondary contextual menu">
		    	<div class="item">
		            <a class="ui icon button" href="{{route('webpage.edit.parent', $webpage)}}">
		                <i class="arrow circle outline right icon"></i>{{ ___('Move Webpage') }}
		            </a>
	            </div>
		    </div>
		@endsection
	@endif

	<input type="hidden" name="parent_id" value="{{$parent ? $parent->id : ''}}">
	<div class="field">
		<label>Parent</label>
		<input type="text" disabled value="{{$parent ? $parent->title : 'No Parent'}}">
	</div>
@endif

<div class="field{{ $errors->has('title') ? ' error' : '' }}">
	<label for="title">Title</label>
	<input type="text" id="title" name="title" placeholder="Title" value="{{ old('title', $webpage->title) }}">
</div>
<div class="field{{ $errors->has('content') ? ' error' : '' }}">
	<label for="content">Content</label>
	<textarea id="content" name="content" class="editor" placeholder="Content">{{ old('content', $webpage->content) }}</textarea>
</div>

<div class="field{{ $errors->has('external_url') ? ' error' : '' }}">
	<label for="external_url">External URL</label>
	<input type="text" id="external_url" name="external_url" placeholder="http://" value="{{ old('external_url', $webpage->getMeta('external_url')) }}">
</div>
