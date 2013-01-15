<?php namespace Cartalyst\Sentry\Cookies;
/**
 * Part of the Sentry Package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Sentry
 * @version    2.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

class NativeCookie implements CookieInterface {

	/**
	 * The key used in the Cookie.
	 *
	 * @var string
	 */
	protected $key = 'cartalyst_sentry';

	/**
	 * Default settings
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Creates a new cookie instance.
	 *
	 * @param  array  $config
	 * @param  string $key
	 * @return void
	 */
	public function __construct(array $config = array(), $key = null)
	{
		// Defining default settings
		$sentryDefaults = array(
			'name'      => $this->key,
			'time'      => time() + 300,
			'domain'    => '',
			'path'      => '/',
			'secure'    => false,
			'httpOnly'  => false,
		);

		// Merge settings
		$this->defaults = array_merge($sentryDefaults, $config);

		if (isset($key))
		{
			$this->key = $key;
		}
	}

	/**
	 * Returns the cookie key.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Put a value in the Sentry cookie.
	 *
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
	public function put($value, $minutes)
	{
		$lifetime = time() + $minutes;

		setcookie(
			$this->getKey(),
			$value,
			$lifetime,
			$this->defaults['path'],
			$this->defaults['domain'],
			$this->defaults['secure'],
			$this->defaults['httpOnly']
		);
	}

	**
	 * Put a value in the Sentry cookie forever.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function forever($value)
	{
		$this->put($this->getKey(), $value, time() + 60*60*24*31*12*5);
	}

	/**
	 * Get the Sentry cookie value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		if (isset($_COOKIE[$this->getKey()]))
		{
			return $_COOKIE[$this->getKey()];
		}
	}

	/**
	 * Remove the sentry cookie
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function forget()
	{
		$this->put($this->getKey(), null, time() - 65535);
	}

}