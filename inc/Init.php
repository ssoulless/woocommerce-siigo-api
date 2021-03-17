<?php
/**
 * @package EnvioclickPlugin
 */
namespace Inc;

final class Init
{
	/**
	 * Stores all the classes inside an Array
	 * @return array 	The full list of classes
	 */
	public static function get_services() 
	{
		return [
			Pages\Admin::class,
			Base\Enqueue::class,
			Base\SettingsLinks::class,
			Api\RestClientApi::class
		];
	}
	
	/**
	 * Loop through the classes, initialize them,
	 * and call he resgister() method if exists
	 * @return 
	 */
	public static function register_services()
	{
		foreach ( self::get_services() as $class) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class 	class from the services array
	 * @return class instance 	The new instance of the class
	 */
	private static function instantiate( $class ) 
	{
		$service = new $class();

		return $service;
	}
}