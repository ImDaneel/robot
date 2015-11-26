<?php

use Robot\Utils\PushService;

class UserController extends \BaseController
{

    public function __construct()
    {
        // csrf check for every post request
        $this->beforeFilter('csrf', ['on'=>'post']);
    }

    /**
     * Display the specified resource.
     * GET /user
     *
     * @return Response
     */
    public function show()
    {
        $id = Auth::id();
        $user = User::findOrFail($id);

        return JsonView::make('success', ['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /user
     *
     * @return Response
     */
    public function update()
    {
        $id = Auth::id();
        $user = User::findOrFail($id);

        $data = Input::except('_token', 'avatar_image', 'verify_code');
        App::make('Robot\Validators\UserUpdateValidator')->validate($data);

        if (isset($data['phone'])) {
            if (! empty($user->phone)) {
                return JsonView::make('error', ['errors'=>'You can not modify phone']);
            }
            
            if (! VerifyCode::verify($data['phone'], Input::get('verify_code'))) {
                return JsonView::make('error', ['errors'=>'verify code error']);
            }
        }

        if (($file = Input::file('avatar_image')) != null) {
            $result = $this->uploadAvatar($id, $file);
            if (isset($result['errors'])) {
                return JsonView::make('error', $result);
            }
            $data['avatar'] = $result['filename'];
        }

        $user->update($data);

        return JsonView::make('success', ['user'=>$user]);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function uploadAvatar($id, $file)
    {
        $allowed_extensions = ["png", "jpg", "gif"];

        if (!$file->isValid()) {
            return ['errors' => 'Error while uploading file.'];
        }

        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ['errors' => 'You may only upload png, jpg or gif.'];
        }

        $ext = $file->getClientOriginalExtension();
        $avatar_name = $id . '_' . time() . '.' . $ext;
        $file->move(public_path('uploads/avatars/'), $avatar_name);

        return ['filename' => Config::get('app.url') . 'uploads/avatars/' . $avatar_name];
    }

    public function getRobots()
    {
        $id = Auth::id();
        $robots = User::findOrFail($id)->robots()->get()->toArray();
        if (empty($robots)) {
            $robots = null;
        }

        return JsonView::make('success', ['robots'=>$robots]);
    }

    public function authResponse()
    {
        $data = Input::only('phone', 'robot_sn', 'token');
        if (! AuthRequest::validate($data)) {
            return JsonView::make('error', ['errors'=>'request data error']);
        }

        $user = Auth::user();
        if (! $user->is_admin || Robot::findBySn($data['robot_sn'])->admin_id != $user->id) {
            return JsonView::make('error', ['errors'=>'You are not admin user']);
        }

        $reply = Input::get('reply');
        if (! PushService::push('authreply', $data['phone'], ['reply'=>$reply, 'robot_sn'=>$data['robot_sn']])) {
            // if not success, save it
            //return JsonView::make('failed', ['errors'=>'push message error']);
        }

        if ($reply == 'accept') {
            $user = User::firstOrCreate(['phone'=>$data['phone']]);
            Robot::findBySn($data['robot_sn'])->addUser($user->id);
        }

        return JsonView::make('success');
    }
}
