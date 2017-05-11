@extends('Content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="world icon"></i>
        <div class="content">
            Webpage Management
            <div class="sub header">Manage the webpage content type.</div>
        </div>
    </h1>
@endsection

@section('page.head.menu')
    <div class="ui secondary menu">
    	<div class="right item">
	        <a class="ui labeled icon button" href="{{ route('webpage.create') }}">
	            <i class="world icon"></i>Add Webpage
	        </a>
	    </div>
    </div>
@endsection

@section('content')
<div class="webpage">

	<table class="ui selectable table">
		<thead>
			<tr>
				<th class="center aligned collapsing">ID</th>
				<!-- <th class="center aligned collapsing">Key</th> -->
				<th>Title</th>
				<th class="center aligned collapsing">Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($webpages as $webpage)
				<tr>
					<td class="collapsing">{{ $webpage->id }}</td>
					<!-- <td class="collapsing">{{ $webpage->key }}</td> -->
					<td>{{ $webpage->title }}</td>
					<td class="right aligned collapsing">
						<div class="ui compact text menu">
							<a href="{{ url($webpage->getUrl()) }}" class="item"><i class="world icon"></i>Visit</a>
							<a href="{{ route('webpage.edit', $webpage->id) }}" class="item"><i class="pencil icon"></i>Edit</a>
							<a href="{{ route('webpage.destroy', $webpage->id) }}" class="item"><i class="delete icon"></i>Delete</a>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

{{ $webpages->links() }}

@endsection