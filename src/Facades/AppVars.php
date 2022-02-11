<?php namespace Gecche\Cupparis\AppVars\Facades;

use Illuminate\Support\Facades\Facade as Facade;

class AppVars extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'app_vars'; }

}
