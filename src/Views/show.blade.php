@extends('Pretzel::admin')
@section('content')

<h1 class="ui header">
    <div class="content">
        {{ $webpage->title }}
    </div>
</h1>
<div class="ui hidden divider"></div>
<div class="ui hidden divider"></div>

{!! $webpage->content !!}

<!-- pagination start -->
<div class="ui hidden divider"></div>
<!-- pagination end -->

@endsection