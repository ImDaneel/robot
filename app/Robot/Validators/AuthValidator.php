<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class AuthValidator extends FormValidator
{
    protected $rules = [
        'phone'       => 'required|numeric|size:11',
        'verify_code' => 'required|numeric',
        'robot_sn'    => 'required|exists:robots,sn',
    ];
}
