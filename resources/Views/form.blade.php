<div class="field">
	<label for="title">Parent</label>
	<select name="parent_id" class="ui dropdown">
		<option value="">No Parent</option>
		@foreach($parents as $item)
		<option value="{{ $item->id }}"@if($webpage->id == $item->id) selected="selected"@endif>{{ $item->title }}</option>
		@endforeach
	</select>
</div>

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

@section('head')
{{-- <link rel="stylesheet" type="text/css" href="/css/trix.css"> --}}
{{-- <script type="text/javascript" src="/js/trix.js"></script> --}}
@endsection