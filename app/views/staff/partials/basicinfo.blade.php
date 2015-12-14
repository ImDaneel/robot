<div style="text-align: center;">
  <a href="">
    <img src="{{ $staff->avatar }}" class="img-thumbnail users-show-avatar" style="width: 206px;margin: 4px 4px 15px;min-height:190px">
  </a>
</div>

<dl class="dl-horizontal">

  <dt><lable>&nbsp; </lable></dt><dd> {{ lang('User ID:') }} {{ $staff->id }}</dd>

  <dt><label>Name:</label></dt><dd><strong>{{{ $staff->name }}}</strong></dd>

  @if ($staff->real_name)
    <dt class="adr"><label> {{ lang('Real Name') }}:</label></dt><dd><span class="org">{{{ $staff->real_name }}}</span></dd>
  @endif

  @if ($staff->signature)
    <dt><label>{{ lang('Signature') }}:</label></dt><dd><span>{{{ $staff->signature }}}</span></dd>
  @endif

  <dt>
    <label>Since:</label>
  </dt>
  <dd><span>{{ $staff->created_at }}</span></dd>
</dl>
<div class="clearfix"></div>

<a class="btn btn-primary btn-block" href="" id="user-edit-button">
  <i class="fa fa-edit"></i> {{ lang('Edit Profile') }}
</a>

