<?php namespace Robot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Staff\Auth\AuthManager
 * @see \Illuminate\Auth\Guard
 */
class StaffAuth extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'staff_auth'; }

}
