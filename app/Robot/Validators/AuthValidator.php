<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class AuthValidator extends FormValidator
{
    protected $rules = [
        'phone'       => 'required|numeric',
        'verify_code' => 'required|numeric',
        'robot_sn'    => 'required|exists:robots,sn',
    ];
}
