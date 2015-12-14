<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;

class ReplyCreationForm extends FormValidator
{
    protected $rules = [
        'body'     => 'required|min:2',
        'staff_id'  => 'required|numeric',
        'feedback_id' => 'required|numeric',
    ];
}
