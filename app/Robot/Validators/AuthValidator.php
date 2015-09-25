<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class AuthValidator extends FormValidator
{
    protected $rules = [
        'phone'       => 'required|numeric',
        'verify_code' => 'required|numeric',
        'robot_sn'    => 'required|exists:robots,sn',
    ];
}
