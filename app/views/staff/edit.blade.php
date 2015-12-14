@extends('layouts.default')

@section('title')
编辑个人资料_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('staff.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default">

      <div class="panel-body ">

        <div class="alert alert-warning">
          {{ lang('avatar_notice') }} {{ link_to_route('staff.update_avatar', lang('Update Avatar'), $staff->id) }} .
        </div>

        @include('layouts.partials.errors')

        {{ Form::model($staff, ['route' => ['staff.update', $staff->id], 'method' => 'patch']) }}

          <div class="form-group">
            {{ Form::text('real_name', null, ['class' => 'form-control', 'placeholder' => lang('Real Name')]) }}
          </div>

          <div class="form-group status-post-submit">
            {{ Form::submit(lang('Publish'), ['class' => 'btn btn-primary', 'id' => 'user-edit-submit']) }}
          </div>


        {{ Form::close() }}

      </div>

    </div>
  </div>


</div>




@stop
