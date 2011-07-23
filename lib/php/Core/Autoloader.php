<?php
namespace Core;

/**
 * This class is inheriting the Base class and the autoload mechanism does not 
 * work yet here.
 *
 * @see Base
 */
require_once __DIR__ . '/Base.php';

/**
 * Autoloader 
 * 
 * @package core
 * @subpackage base
 * @version $id$
 * @copyright 
 * @author Pablo López Torres <pablolopeztorres@gmail.com> 
 * @license 
 */
class Autoloader extends Base
{
	/**
	 * instance 
	 * 
	 * @var Autoloader
	 */
	private static $instance;

	/**
	 * Singleton method
	 * 
	 * @author Pablo López Torres <pablo@tuenti.com> 
	 * @return Autoloader
	 */
	public static function i() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
     * Constructor of the class made protected for assuring singletoness.
	 * 
	 * @author Pablo López Torres <pablo@tuenti.com> 
	 * @return void
	 */
	protected function __construct()
	{

	}
	

	public function register()
	{
		spl_autoload_register(array($this, 'autoloadClass'));
	}

	public function unregister()
	{
		spl_autoload_unregister(array($this, 'autoloadClass'));
	}

	public function autoloadClass($className)
	{
		$filename = LIB_PATH . str_replace('\\', '/', $className) . '.php';
		if (file_exists($filename))
		{
			require_once $filename;
		}
	}
}

