<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class ReportValidator extends FormValidator
{
    protected $rules = [
        'clean_time' => 'required|date_format:Y-m-d H:i:s',
        'area'       => 'required|numeric',
        'duration'   => 'required|numeric',
        'percent'    => 'sometimes|numeric|between:0,100',
    ];
}
