<?php

use LaravelBook\Ardent\Ardent;
use Markdown;

class Feedback extends Ardent
{
    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $hidden = ['updated_at', 'deleted_at'];

    public $throwOnValidation = true;

    public static $rules = [
        'title'   => 'required|min:2',
        'body'    => 'required|min:2',
        'user_id' => 'required|exists:users,id',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function replies()
    {
        return $this->hasMany('Reply');
    }

    public function setBodyAttribute($value)
    {
        $markdown = new Markdown;
        $this->attributes['body'] = $markdown->convertMarkdownToHtml($value);
    }

    public static function increaseReplyCount($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $feedback->reply_count++;
        $feedback->save();
    }
}
