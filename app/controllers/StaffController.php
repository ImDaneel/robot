<?php

use Robot\Listeners\UserCreatorListener;
use Robot\Listeners\AuthenticatorListener;

class StaffController extends BaseController implements AuthenticatorListener, UserCreatorListener
{
    public function login()
    {
        return View::make('staff.login');
    }

    public function logout()
    {
        StaffAuth::logout();
        Flash::success(lang('Operation succeeded.'));
        return Redirect::route('staff.login');
    }

    public function authenticate()
    {
        $data = Input::only('name', 'password');
        App::make('Robot\Validators\StaffLoginForm')->validate($data);

        if (StaffAuth::attempt($data, true)) {
            //return Redirect::to('staff/home');
            return Redirect::to('feedback');
        } else {
            return Redirect::to('staff/login')->with('message', '用户名或密码错误')->withInput();
        }
    }

    public function home()
    {
        return View::make('staff.home');
    }

    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        $replies = Reply::whose($staff->id)->recent()->limit(10)->get();
        $traces = null;

        return View::make('staff.show', compact('staff', 'replies', 'traces'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $this->authorOrAdminPermissioinRequire($staff->id);

        return View::make('staff.edit', compact('staff'));
    }

    public function update()
    {
        $staff = StaffAuth::user();
        $this->authorOrAdminPermissioinRequire($staff->id);
        $data = Input::only('real_name');
        App::make('Phphub\Forms\UserUpdateForm')->validate($data);

        $user->update($data);

        Flash::success(lang('Operation succeeded.'));

        return Redirect::route('users.show', $id);
    }

    public function updateAvatar()
    {
        $user = User::findOrFail($id);
        $this->authorOrAdminPermissioinRequire($user->id);

        return View::make('staff.update-avatar', compact('staff'));
    }

    public function uploadAvatar($id)
    {
        $result = Robot\Utils\Image::upload(Input::file('image'), 'staff_' . StaffAuth::user()->id);

        if (isset($result['errors'])) {
            Flash::error(lang($result['errors']));
        } else {
            //Save to database
            $staff->avatar = $avatar_name;
            $user->save();

            Flash::success(lang('Operation succeeded.'));
        }

        return Redirect::route('users.edit', $id);
    }

    public function adminRequired()
    {
        return View::make('auth.adminrequired');
    }

    /**
     * Shows a user what their new account will look like.
     */
    public function create()
    {
        /* 
        if (! Session::has('userGithubData')) {
            return Redirect::route('login');
        }
        $githubUser = array_merge(Session::get('userGithubData'), Session::get('_old_input', []));
        return View::make('auth.signupconfirm', compact('githubUser'));
        */
        return View::make('auth.signupconfirm');
    }

    /**
     * Actually creates the new user account
     */
    public function store()
    {
        /*
        if (! Session::has('userGithubData')) {
            return Redirect::route('login');
        }
        $githubUser = array_merge(Session::get('userGithubData'), Input::only('name', 'github_name', 'email'));
        unset($githubUser['emails']);
        return App::make('Phphub\Creators\UserCreator')->create($this, $githubUser);
        */

        $userData = Input::only('name', 'password', 'password_confirmation', 'email');
        return App::make('Phphub\Creators\UserCreator')->create($this, $userData);
    }

    public function userBanned()
    {
        if (Auth::check() && !Auth::user()->is_banned) {
            return Redirect::route('home');
        }

        //force logout
        Auth::logout();
        return View::make('auth.userbanned');
    }

    /**
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */

    public function userValidationError($errors)
    {
        Flash::error(lang('Operation failed!'));
        return Redirect::to('/');
    }

    public function userCreated($user)
    {
        Auth::login($user, true);
        Session::forget('userGithubData');

        Flash::success(lang('Congratulations and Welcome!'));

        return Redirect::intended();
    }

    /**
     * ----------------------------------------
     * GithubAuthenticatorListener Delegate
     * ----------------------------------------
     */

    // 数据库找不到用户, 执行新用户注册
    public function userNotFound()
    {
        // Session::put('userGithubData', $githubData);
        // return Redirect::route('signup');
        return View::make('auth.usernotfound');
    }

    // 数据库有用户信息, 登录用户
    public function userFound($user)
    {
        Auth::login($user, true);
        Session::forget('userGithubData');

        Flash::success(lang('Login Successfully.'));

        return Redirect::intended();
    }

    // 用户屏蔽
    public function userIsBanned($user)
    {
        return Redirect::route('user-banned');
    }
}
