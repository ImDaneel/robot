

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

            @if ($feedback->vote_count > 0)
                <a href="{{ route('feedback.show', [$feedback->id]) }}" class="remove-padding-left" id="pin-{{ $feedback->id }}">
                    <span class="fa fa-thumbs-o-up"> {{ $feedback->vote_count }} </span>
                </a>
                <span> •  </span>
            @endif

            @if ($feedback->reply_count == 0)
                <a href="#" title="{{{ $feedback->user->name }}}">
                    {{{ $feedback->user->getName() }}}
                </a>
                <span> • </span>
                <span class="timeago">{{ $feedback->created_at }}</span>
            @endif

            @if ($feedback->reply_count > 0 && count($feedback->lastReplyUser))
                <span> • </span>{{ lang('Last Reply by') }}
                <a href="{{{ URL::route('users.show', [$feedback->lastReplyUser->id]) }}}">
                  {{{ $feedback->lastReplyUser->name }}}
                </a>
                <span> • </span>
                <span class="timeago">{{ $feedback->updated_at }}</span>
            @endif
          </div>

        </div>

    </li>
    @endforeach
</ul>

@else
   <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
@endif
