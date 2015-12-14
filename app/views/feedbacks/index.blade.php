@extends('layouts.default')

@section('content')


<div class="panel panel-default list-panel">
  <div class="panel-heading">
    <h3 class="panel-title text-center">
      {{ lang('User Feedbacks') }} &nbsp;
    </h3>

  </div>

  <div class="panel-body">
	@include('feedbacks.partials.feedbacks', ['column' => false])
  </div>

</div>

@stop
