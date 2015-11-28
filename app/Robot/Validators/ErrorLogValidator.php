<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class ErrorLogValidator extends FormValidator
{
    protected $rules = [
        'code' => 'required',
    ];
}
