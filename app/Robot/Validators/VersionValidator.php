<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class VersionValidator extends FormValidator
{
    protected $rules = [
        'type' => 'required|in:robot,ios,android',
    ];
}
