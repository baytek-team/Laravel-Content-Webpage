@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="world icon"></i>
        <div class="content">
            {{ ___('Webpage Management') }}
            <div class="sub header">{{ ___('Manage the webpage content type.') }}</div>
        </div>
    </h1>
@endsection

@section('page.head.menu')
	<div class="ui secondary menu contextual">
		<div class="item">
			<form class="{{ count($errors) != 0 ? ' error' : '' }}" method="GET" action="{{ route('webpage.index')}}" style="display:inline">
		        <div class="ui left icon right action input">
		            <input type="text" placeholder="{{ ___('Enter search query') }}" name="search" value="{{ collect(Request::instance()->query)->get('search') }}">
		            <i class="search icon"></i>
		            <button type="submit" class="ui basic button">{{ ___('Search') }}</button>
		        </div>
		    </form>
	    </div>

		@if(!Route::is('webpage.index'))
			@if($parent)
				<a class="item" href="{{ route('webpage.show', $parent->id) }}">
					<i class="level up icon"></i>Back to {{ $parent->title }}
				</a>
			@else
				<a class="item" href="{{ route('webpage.index') }}">
					<i class="level up icon"></i>Back to Webpages
				</a>
			@endif
		@endif

		<div class="item">
		    @if(isset($webpage))
		    	@link(___('Add Webpage'), [
		    	    'location' => 'webpage.create.child',
		    	    'type' => 'route',
		    	    'class' => 'ui primary button',
		    	    'prepend' => '<i class="world icon"></i>',
		    	    'model' => [
		    	    	'webpage' => $webpage
		    	    ]
		    	])
		    @else
		    	@link(___('Add Webpage'), [
		    	    'location' => 'webpage.create',
		    	    'type' => 'route',
		    	    'class' => 'ui primary button',
		    	    'prepend' => '<i class="world icon"></i>',
		    	])
		    @endif
	    </div>
	</div>
@endsection

@section('content')
	<table class="ui very basic table">
		<thead>
			<tr>
				<th>{{ ___('Title') }}</th>
				<th class="center aligned collapsing">{{ ___('Actions') }}</th>
			</tr>
		</thead>
		<tbody>
			@forelse($webpages as $webpage)
				<tr>
					<td>
						<a href="{{ route('webpage.show', $webpage) }}" class="item">
							<i class="level down icon"></i>
							{{-- {{ ___('Children') }} --}}
						</a>

						<a href="{{ route('webpage.edit', $webpage) }}" class="item">
							{{-- <i class="level down icon"></i> --}}
							{{ $webpage->title }}
						</a>
					</td>
					<td class="right aligned collapsing">
						<div class="ui compact text menu">

							<a href="{{ route('webpage.edit', $webpage) }}" class="item">
								<i class="pencil icon"></i>
							</a>
							@link('', [
								'class' => 'action item',
								'confirm' => 'Are you sure you want to delete this webpage?',
								'location' => 'webpage.destroy',
								'method' => 'delete',
								'model' => $webpage,
								'prepend' => '<i class="delete icon"></i>',
								'type' => 'route',
							])
						</div>
					</td>
				</tr>
			@empty
				<tr>
	                <td colspan="2">
	                    <div class="ui centered">{{ ___('There are no results') }}</div>
	                </td>
	            </tr>
			@endforelse
		</tbody>
	</table>

	{{ $webpages->links('pagination.default') }}
@endsection