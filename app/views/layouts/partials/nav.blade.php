<div role="navigation" class="navbar navbar-default navbar-static-top topnav">
  <div class="container">
    <div class="navbar-header">

      <a href="{{ route('staff.home') }}" class="navbar-brand">Home</a>
    </div>
    <div id="top-navbar-collapse" class="navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="{{ (Request::is('feedbacks*') ? ' active' : '') }}"><a href="{{ route('feedback.index') }}">{{ lang('Feedbacks') }}</a></li>
        <li class="{{ (Request::is('traces*') ? ' active' : '') }}"><a href="">{{ lang('Traces') }}</a></li>
        <li class="{{ (Request::is('versions*') ? ' active' : '') }}"><a href="">{{ lang('Versions') }}</a></li>
      </ul>

      <div class="navbar-right">

        <ul class="nav navbar-nav github-login" >
          @if (StaffAuth::check())
              <li>
                  <a href="{{ route('staff.show', $currentStaff->id) }}">
                      <i class="fa fa-user"></i> {{{ $currentStaff->name }}}
                  </a>
              </li>
              <li>
                  <a class="button" href="{{ URL::route('staff.logout') }}" onclick=" return confirm('{{ lang('Are you sure want to logout?') }}')">
                      <i class="fa fa-sign-out"></i> {{ lang('Logout') }}
                  </a>
              </li>
          @else
              <a href="{{ URL::route('staff.login') }}" class="btn btn-info" id="login-btn">
                <i class="fa fa-github-alt"></i>
                {{ lang('Login') }}
              </a>
          @endif
        </ul>
      </div>
    </div>

  </div>
</div>
