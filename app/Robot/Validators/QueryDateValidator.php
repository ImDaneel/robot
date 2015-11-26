<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class QueryDateValidator extends FormValidator
{
    protected $rules = [
        'begin' => 'sometimes|date_format:Y-m-d',
        'end'   => 'sometimes|date_format:Y-m-d',
    ];

}
