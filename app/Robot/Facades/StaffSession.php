<?php namespace Robot\Facades;

/**
 * @see \Staff\Session\StaffSessionManager
 * @see \Illuminate\Session\Store
 */
class StaffSession extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'staff_session'; }

}
