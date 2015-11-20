<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class ScheduleValidator extends FormValidator
{
    protected $rules = [
        'schedule_time' => 'required|date_format:H:i:s',
        'repeat'        => 'sometimes|array',
        'schedule_on'   => 'sometimes|boolean',
    ];

    public function validate($formData)
    {
        parent::validate($formData);

        if (isset($formData['repeat'])) {
            foreach($formData['repeat'] as $day) {
                if ($day < 1 || $day > 7) {
                    throw new FormValidationException('Validation failed', ['repeat' =>'The repeat out of range.']);
                }
            }
        }

        return true;
    }
}
