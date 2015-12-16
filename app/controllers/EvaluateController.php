<?php

class EvaluateController extends \BaseController
{
    /**
     * Store a newly created resource in storage.
     * POST /evaluate
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::only('feedback_id', 'grade', 'comment');

        if (Feedback::findOrFail($data['feedback_id'])->evaluation) {
            return JsonView::make('error', ['errors' => 'You have already evaluated.']);
        }

        $evaluation = Evaluation::create($data);

        return JsonView::make('success');
    }
}
