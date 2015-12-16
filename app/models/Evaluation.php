<?php

use LaravelBook\Ardent\Ardent;
use Markdown;

class Evaluation extends Ardent
{
    protected $guarded = ['id'];

    protected $hidden = ['id', 'updated_at'];

    public $throwOnValidation = true;

    public static $rules = [
        'feedback_id' => 'required|exists:feedbacks,id',
        'grade'       => 'required|numeric|between:1,5',
        'comment'     => 'sometimes|min:1',
    ];

    public function feedback()
    {
        return $this->belongsTo('Feedback');
    }

    public function setCommentAttribute($value)
    {
        if (! $value) {
            return;
        }
        $markdown = new Markdown;
        $this->attributes['comment'] = $markdown->convertMarkdownToHtml($value);
    }
}
