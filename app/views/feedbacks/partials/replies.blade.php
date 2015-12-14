<ul class="list-group row">

  @foreach ($replies as $index => $reply)
   <li class="list-group-item media"
           @if($reply->vote_count >= 2)
                style="margin-top: 0px; background-color: #fffce9"
           @else
                style="margin-top: 0px;"
           @endif
           >

    <div class="avatar pull-left">
      <a href="#">
        <img class="media-object img-thumbnail avatar" alt="{{{ $reply->staff->getName() }}}" src="{{ $reply->staff->avatar }}"  style="width:48px;height:48px;"/>
      </a>
    </div>

    <div class="infos">

      <div class="media-heading meta">

        <a href="#" title="{{{ $reply->staff->getName() }}}" class="remove-padding-left author">
            {{{ $reply->staff->getName() }}}
        </a>
        <span> •  </span>
        <abbr class="timeago" title="{{ $reply->created_at }}">{{ $reply->created_at }}</abbr>
        <span> •  </span>
        <a name="reply{{ $index+1 }}" class="anchor" href="#reply{{ $index+1 }}" aria-hidden="true">#{{ $index+1 }}</a>

      </div>

      <div class="media-body markdown-reply content-body">
        {{ $reply->body }}
      </div>

    </div>

  </li>
  @endforeach

</ul>
