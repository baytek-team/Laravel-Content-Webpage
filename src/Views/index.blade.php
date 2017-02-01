@extends('Content::admin')
@section('content')
<div class="webpage" style="padding-top: 100px">
	<div class="ui text aligned center header">
		<h1 style="font-size: 48px;margin-bottom: 100px">
			List of all webpages
		</h1>
	</div>


	<table class="ui celled table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Key</th>
				<th>Title</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($webpages as $webpage)
				<tr>
					<td>{{ $webpage->id }}</td>
					<td>{{ $webpage->key }}</td>
					<td>{{ $webpage->title }}</td>
					<td>
						<a href="{{ route('webpage.edit', $webpage) }}" class="ui button primary">Edit</a>
						<a href="{{ route('webpage.destroy', $webpage) }}" class="ui button warning">Delete</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</div>

@endsection