<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class RegisterValidator extends FormValidator
{
    protected $rules = [
        'phone'       => 'numeric|required|unique:users',
        'password'    => 'required|size:32',
    ];
}
