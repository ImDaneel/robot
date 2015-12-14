<?php

use Markdown;

class Reply extends \Eloquent
{
    protected $guarded = ['id'];

    protected $hidden = ['updated_at', 'deleted_at'];

    public function feedback()
    {
        return $this->belongsTo('Feedback');
    }

    public function staff()
    {
        if ($this->staff_id) {
            return $this->belongsTo('Staff');
        } else {
            return $this->feedback()->user();
        }
    }

    public function setBodyAttribute($value)
    {
        $markdown = new Markdown;
        $this->attributes['body'] = $markdown->convertMarkdownToHtml($value);
    }

    public function scopeWhose($query, $staff_id)
    {
        return $query->where('staff_id', '=', $staff_id)->with('feedback');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
