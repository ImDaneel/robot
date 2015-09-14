<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class UserLoginValidator extends FormValidator
{
    protected $rules = [
        'phone'           => 'numeric|required',
        'password'        => 'required|size:32',
    ];
}
