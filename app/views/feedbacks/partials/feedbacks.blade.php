

@if (count($feedbacks))

<ul class="list-group row feedback-list">
    @foreach ($feedbacks as $feedback)
     <li class="list-group-item media {{ !$column ?:'col-sm-6'; }}" style="margin-top: 0px;">

        <a class="pull-right" href="{{ route('feedback.show', [$feedback->id]) }}" >
            <span class="badge badge-reply-count"> {{ $feedback->reply_count }} </span>
        </a>

        <div class="avatar pull-left">
            <a href="#">
                <img class="media-object img-thumbnail avatar" alt="{{{ $feedback->user->name }}}" src="{{ $feedback->user->avatar }}"  style="width:48px;height:48px;"/>
            </a>
        </div>

        <div class="infos">

          <div class="media-heading">
            <a href="{{ route('feedback.show', [$feedback->id]) }}" title="{{{ $feedback->title }}}">
                {{{ $feedback->title }}}
            </a>
          </div>

          <div class="media-body meta">

            <a href="#" title="{{{ $feedback->user->name }}}">
                {{{ $feedback->user->getName() }}}
            </a>
            <span> • </span>
            <span class="timeago">{{ $feedback->created_at }}</span>

          </div>

        </div>

    </li>
    @endforeach
</ul>

@else
   <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
@endif
