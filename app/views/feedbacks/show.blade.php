@extends('layouts.default')

@section('title')
{{{ $feedback->title }}}_@parent
@stop

@section('description')
{{{ $feedback->excerpt }}}
@stop

@section('content')

<div class="col-md-9 feedbacks-show main-col">

  <!-- Topic Detial -->
  <div class="feedback panel panel-default">
    <div class="infos panel-heading">

      <div class="pull-right avatar_large">
        <a href="#">
          <img src="{{ $feedback->user->avatar }}" style="width:65px; height:65px;" class="img-thumbnail avatar" />
        </a>
      </div>

      <h1 class="panel-title feedback-title">{{{ $feedback->title }}}</h1>

      @include('feedbacks.partials.meta')
    </div>

    <div class="content-body entry-content panel-body">

      @include('feedbacks.partials.body', array('body' => $feedback->body))

    </div>
  </div>

  <!-- Reply List -->
  <div class="replies panel panel-default list-panel replies-index">
    <div class="panel-heading">
      <div class="total">{{ lang('Total Reply Count') }}: <b>{{ $feedback->reply_count }}</b> </div>
    </div>

    <div class="panel-body">

      @if (count($replies))
        @include('feedbacks.partials.replies')
      @else
         <div class="empty-block">{{ lang('No comments') }}~~</div>
      @endif

    </div>
  </div>

  <!-- Reply Box -->
  <div class="reply-box form box-block">

    @include('layouts.partials.errors')

    {{ Form::open(['route' => 'reply.store', 'id' => 'reply-form', 'method' => 'post']) }}
      <input type="hidden" name="feedback_id" value="{{ $feedback->id }}" />

        @include('feedbacks.partials.composing_help_block')

        <div class="form-group">
            @if ($currentStaff)
              {{ Form::textarea('body', null, ['class' => 'form-control',
                                                'rows' => 5,
                                                'style' => "overflow:hidden",
                                                'id' => 'reply_content']) }}
            @else
              {{ Form::textarea('body', null, ['class' => 'form-control', 'disabled' => 'disabled', 'rows' => 5, 'placeholder' => lang('User Login Required for commenting.')]) }}
            @endif
        </div>

        <div class="form-group status-post-submit">

            @if ($currentStaff)
              {{ Form::submit(lang('Reply'), ['class' => 'btn btn-primary', 'id' => 'reply-create-submit']) }}
            @else
              {{ Form::submit(lang('Reply'), ['class' => 'btn btn-primary disabled', 'id' => 'reply-create-submit']) }}
            @endif

            <span class="help-inline" title="Or Command + Enter">Ctrl+Enter</span>
        </div>

        <div class="box preview markdown-reply" id="preview-box" style="display:none;"></div>

    {{ Form::close() }}
  </div>

</div>

@stop
