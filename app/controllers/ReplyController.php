<?php

class ReplyController extends \BaseController
{

    /**
     * Store a newly created resource in storage.
     * POST /reply
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::only('body', 'feedback_id');
        $data['staff_id'] = StaffAuth::id();

        // Validation
        App::make('Robot\Validators\ReplyCreationForm')->validate($data);

        $reply = Reply::create($data);

        Feedback::increaseReplyCount($data['feedback_id']);

        Flash::success(lang('Operation succeeded.'));
        return Redirect::route('feedback.show', array($data['feedback_id'], '#reply'.$reply->id));
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /reply/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadImage()
    {
        return Robot\Utils\Image::upload(Input::file('file'), 'staff_' . StaffAuth::user()->id);
    }

}
