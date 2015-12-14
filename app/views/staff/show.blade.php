@extends('layouts.default')

@section('title')
{{{ $staff->name }}} {{ lang('Basic Info') }}_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('staff.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    @if ($staff->is_banned)
      <div class="text-center alert alert-info"><b>{{ lang('This user is banned!') }}</b></div>
    @endif

    <div class="panel panel-default">

      <ul class="nav nav-tabs user-info-nav" role="tablist">
        <li class="active"><a href="#recent_replies" role="tab" data-toggle="tab">{{ lang('Recent Replies') }}</a></li>
        <li><a href="#recent_traces" role="tab" data-toggle="tab">{{ lang('Recent Traces') }}</a></li>
      </ul>

      <div class="panel-body remove-padding-vertically remove-padding-horizontal">
        <!-- Tab panes -->
        <div class="tab-content">

          <div class="tab-pane active" id="recent_replies">

            @if (count($replies))
              @include('staff.partials.replies')
            @else
              <div class="empty-block">{{ lang('Dont have any comment yet') }}~~</div>
            @endif

          </div>

          <div class="tab-pane" id="recent_traces">
            @if (count($traces))
              @include('staff.partials.traces')
            @else
              <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            @endif
          </div>

        </div>
      </div>

    </div>
  </div>

</div>

@stop
