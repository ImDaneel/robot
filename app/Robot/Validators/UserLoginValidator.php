<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class UserLoginValidator extends FormValidator
{
    protected $rules = [
        'phone'       => 'numeric|required',
        'password'    => 'sometimes|required|size:32',
        'verify_code' => 'sometimes|required|numeric',
    ];
}
