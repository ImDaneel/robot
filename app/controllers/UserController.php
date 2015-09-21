<?php

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

        $data = Input::except('_token', 'avatar_image');
        App::make('Robot\Validators\UserUpdateValidator')->validate($data);

        if (isset($data['phone'])) {
            //check dynamic pin
        }

        if (($file = Input::file('avatar_image')) != null) {
            $result = $this->uploadAvatar($id, $file);
            if (isset($result['error'])) {
                return JsonView::make('failed', $result);
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
            return ['error' => 'Error while uploading file.'];
        }

        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg or gif.'];
        }

        $ext = $file->getClientOriginalExtension();
        $avatar_name = $id . '_' . time() . '.' . $ext;
        $file->move(public_path('uploads/avatars/'), $avatar_name);

        return ['filename' => Config::get('app.url') . 'uploads/images/' . $avatar_name];
    }

    public function getRobots()
    {
        $id = Auth::id();
        $robots = User::findOrFail($id)->robots()->get()->toArray();

        return JsonView::make('success', ['robots'=>$robots]);
    }

}
