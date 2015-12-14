<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class StaffLoginForm extends FormValidator
{
    protected $rules = [
        'name'            => 'required|alpha_num|min:2',
        'password'        => 'required|alpha_num|between:6,20',
    ];
}
