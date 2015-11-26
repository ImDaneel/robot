<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class MapValidator extends FormValidator
{
    protected $rules = [
        'resolution'      => 'required|numeric',
        'origin'          => 'required|array',
        'occupied_thresh' => 'required|numeric',
        'free_thresh'     => 'required|numeric',
    ];

    public function validate($formData)
    {
        parent::validate($formData);

        if (count($formData['origin']) != 3) {
            throw new FormValidationException('Validation failed', ['origin' =>'The origin must have 3 elements.']);
        }

        foreach($formData['origin'] as $p) {
            if (! is_numeric($p)) {
                throw new FormValidationException('Validation failed', ['origin' =>'The origin must be numeric.']);
            }
        }

        return true;
    }
}
