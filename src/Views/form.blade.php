<!-- <div class="field{{ $errors->has('key') ? ' error' : '' }}">
	<label for="key">Key</label>
	<input type="text" id="key" name="key" placeholder="Key" value="{{ old('key', $webpage->key) }}">
</div> -->

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
	<textarea id="content" name="content" placeholder="Content">{{ old('content', $webpage->content) }}</textarea>
</div>

{{--
<h4 class="ui horizontal divider header">
	<i class="tags icon"></i>
	Metadata
</h4>

<input type="hidden" name="meta_ids" value="{{ json_encode($webpage->meta->pluck('id')) }}">

@foreach($webpage->meta as $meta)
@if($meta == $webpage->meta->first())
<div class="two fields">
	<div class="field">
		<label>Meta Key</label>
	</div>
	<div class="field">
		<label>Meta Value</label>
	</div>
</div>
@endif

<div class="two fields">
	<div class="field{{ $errors->has('meta_key') ? ' error' : '' }}">
		<input type="text" name="meta_key[{{$meta->id}}]" placeholder="Meta Key" value="{{ $meta->key }}">
	</div>
	<div class="field{{ $errors->has('meta_value') ? ' error' : '' }}">
		<input type="text" name="meta_value[{{$meta->id}}]" placeholder="Meta Value" value="{{ $meta->value }}">
	</div>
	<button type="button" class="ui right floated negative icon button remove-row">
	    <i class="remove icon"></i>
	</button>
</div>
@endforeach

<div class="two fields">
	<div class="field{{ $errors->has('meta_key') ? ' error' : '' }}">
		<input type="text" name="meta_key[]" placeholder="Meta Key" value="">
	</div>
	<div class="field{{ $errors->has('meta_value') ? ' error' : '' }}">
		<input type="text" name="meta_value[]" placeholder="Meta Value" value="">
	</div>
	<button type="button" class="ui right floated positive icon button add-row">
	    <i class="add icon"></i>
	</button>
</div>


<h4 class="ui horizontal divider header">
	<i class="users icon"></i>
	Relationships
</h4>

<input type="hidden" name="relation_ids" value="{{ json_encode($webpage->relations->pluck('id')) }}">

@foreach($webpage->relations as $relation)

@if($relation == $webpage->relations->first())
<div class="three fields">
	<div class="field">
		<label>Content</label>
	</div>
	<div class="field">
		<label>Relation</label>
	</div>
	<div class="field">
		<label>Relation Type</label>
	</div>
</div>
@endif

<div class="three fields">
	<div class="field{{ $errors->has('key') ? ' error' : '' }}">
		<select name="content_id[{{$relation->id}}]" class="ui dropdown disabled">
			@foreach($webpages as $item)
			<option value="{{ $item->id }}"@if($webpage->id == $item->id) selected="selected"@endif>{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<div class="field{{ $errors->has('content') ? ' error' : '' }}">
		<select name="relation_id[{{$relation->id}}]" class="ui dropdown">
			@foreach($webpages as $item)
			<option value="{{ $item->id }}"@if($relation->relation_id == $item->id) selected="selected"@endif>{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<div class="field{{ $errors->has('content') ? ' error' : '' }}">
		<select name="relation_type_id[{{$relation->id}}]" class="ui dropdown">
			@foreach($relationTypes as $item)
			<option value="{{ $item->id }}"@if($relation->relation_type_id == $item->id) selected="selected"@endif>{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<button type="button" class="ui right floated negative icon button remove-row">
	    <i class="remove icon"></i>
	</button>
</div>
@endforeach

<div class="three fields">
	<div class="field">
		<select name="content_id[]" class="ui fluid dropdown disabled">
			<option value="">Select Content</option>
			@foreach($webpages as $item)
			<option value="{{ $item->id }}"@if($webpage->id == $item->id) selected="selected"@endif>{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<div class="field">
		<select name="relation_id[]" class="ui fluid dropdown">
			<option value="">Select Related Content</option>
			@foreach($webpages as $item)
			<option value="{{ $item->id }}">{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<div class="field">
		<select name="relation_type_id[]" class="ui fluid dropdown">
			<option value="">Select Relationship Type</option>
			@foreach($relationTypes as $item)
			<option value="{{ $item->id }}">{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
	<button type="button" class="ui right floated positive icon button add-row">
	    <i class="add icon"></i>
	</button>
</div>
--}}