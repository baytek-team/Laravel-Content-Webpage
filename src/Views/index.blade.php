@extends('Pretzel::admin')
@section('content')

<h1 class="ui header">
    <i class="user icon"></i>
    <div class="content">
        Webpage Management
        <div class="sub header">Manage the Webpages of the claims application.</div>
    </div>
</h1>
<div class="ui hidden divider"></div>
<div class="ui hidden divider"></div>

@foreach($webpages as $webpage)
    {{ $webpage->title }}
@endforeach

<!-- pagination start -->
<div class="ui hidden divider"></div>
<!-- pagination end -->

@endsection