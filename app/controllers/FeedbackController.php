<?php

class FeedbackController extends \BaseController
{
    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
    }

    /**
     * Display a listing of the resource.
     * GET /feedback
     *
     * @return Response
     */
    public function index()
    {
        $feedbacks = Feedback::all();

        return View::make('feedbacks.index', compact('feedbacks'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /feedback
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::only('title', 'body');
        $data['user_id'] = Auth::id();

        $feedback = Feedback::create($data);

        return JsonView::make('success');
    }

    /**
     * Display the specified resource.
     * GET /feedback/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);
        $replies = $feedback->replies()->orderBy('created_at', 'asc')->get();

        if (Request::wantsJson()) {
            return JsonView::make('success', ['feedback' => $feedback, 'replies' => $replies]);
        } else {
            return View::make('feedbacks.show', compact('feedback', 'replies'));
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /feedback/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /feedback/{id}
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
        $result = Robot\Utils\Image::upload(Input::file('file'), Auth::user()->id);

        return JsonView::make(isset($result['errors']) ? 'error' : 'success', $result);
    }

}
