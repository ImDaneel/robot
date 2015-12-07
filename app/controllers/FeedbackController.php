<?php

class FeedbackController extends \BaseController
{
    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);

        $this->beforeFilter('auth', ['only' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     * GET /feedback
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /feedback/create
     *
     * @return Response
     */
    public function create()
    {
        //
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
        $replies = $feedback->replies()->orderBy('created_at', 'asc')->with('staff');

        return JsonView::make('success', ['feedback' => $feedback, 'replies' => $replies]);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /feedback/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        if ($file = Input::file('file')) {
            $allowed_extensions = ["png", "jpg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return JsonView::make('error', ['errors' => 'You may only upload png, jpg or gif.']);
            }

            $fileName        = $file->getClientOriginalName();
            $extension       = $file->getClientOriginalExtension() ?: 'png';
            $folderName      = 'uploads/images/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;
            $destinationPath = public_path() . '/' . $folderName;
            $safeName        = str_random(10).'.'.$extension;
            $file->move($destinationPath, $safeName);

            // If is not gif file, we will try to reduse the file size
            if ($file->getClientOriginalExtension() != 'gif') {
                // open an image file
                $img = Image::make($destinationPath . '/' . $safeName);
                // prevent possible upsizing
                $img->resize(1440, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                // finally we save the image as a new file
                $img->save();
            }

            return JsonView::make('success', ['filename' => Config::get('app.url') . $folderName .'/'. $safeName]);
        }

        return JsonView::make('error', ['errors' => 'Error while uploading file']);
    }

}
