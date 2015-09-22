<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class UserUpdateValidator extends FormValidator
{
    protected $rules = [
        'nick_name'     => 'alpha_dash',
        'tandby_phone'  => 'numeric',
    ];
}
