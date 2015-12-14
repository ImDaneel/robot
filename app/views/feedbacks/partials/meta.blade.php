<div class="meta inline-block" >

  <a href="#">
    {{{ $feedback->user->getName() }}}
  </a>
  •
  {{ lang('at') }} <abbr title="{{ $feedback->created_at }}" class="timeago">{{ $feedback->created_at }}</abbr>

</div>
<div class="clearfix"></div>
