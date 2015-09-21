<?php  namespace Robot\Validators;

use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class InitializeValidator extends FormValidator
{
    protected $rules = [
        'app_type'    => 'required|in:iosAPP,androidAPP',
        'robot_sn'    => 'required|unique:robots,sn',
    ];

    public function validate($formData)
    {
        parent::validate($formData);

        if (!$this->hasRobotSn($formData['robot_sn']))
        {
            throw new FormValidationException('Validation failed', ['robot_sn' =>'robot sn does not exist.']);
        }

        return true;
    }

    private function hasRobotSn($sn)
    {
        return true;
    }
}
