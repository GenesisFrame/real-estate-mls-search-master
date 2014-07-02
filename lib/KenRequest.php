<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/

class KenRequest {
	
	public function __get($name)
	{
		return $this -> query($name);
	}
	
	public function __set($name, $value)
	{
		$_POST[$name] = $value;
		$_GET[$name] = $value;	 
	}
	
	public function __isset($name) {
		if (isset($_GET[$name]) || isset($_POST[$name])) return true;
		else return false;
	}
	
	/**
	 * English:
	 * Get data from GET array
	 * 
	 * Russian:
	 * Получить переданный параметр из массива GET
	 * 
	 * @param string Key of GET array $key
	 * @param mixed $default
	 * @param boolean $clear. True if need strip tags 
	 */
	static public function get($key, $default = null, $clear=true) 
	{
		if (!isset($_GET[$key]) || $_GET[$key] == '') {
			return $default;
		}
		
		if (is_array($_GET[$key]) && $clear == true) {
			$value = ($_GET[$key]);
			array_walk_recursive($value, array('KenRequest', 'clearInputValue'));
			return $value;
		} elseif ($clear == true) {
			return strip_tags($_GET[$key]);
		} else {
			return $_GET[$key];
		}
	}
	
	/**
	 * Russian:
	 * Получить значение типа Integer из массива GET
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function getInt($key, $default = 0)
	{
		if (!isset($_GET[$key]) || $_GET[$key] == '') {
			return $default;
		}
		return intval($_GET[$key]);
	}
	
	/**
	 * Russian:
	 * Получить значение типа Float из массива GET
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function getFloat($key, $default = 0.0)
	{
		if (!isset($_GET[$key]) || $_GET[$key] == '') {
			return $default;
		}
		
		return floatval($_GET[$key]);
	}
	
	/**
	 * English:
	 * Get data from POST array
	 * 
	 * Russian:
	 * Получить переданный параметр из массива POST
	 * 
	 * @param string Key of POST array $key
	 * @param mixed $default
	 * @param boolean $clear. True if need strip tags 
	 */
	static public function post($key, $default = null, $clear=true) {
		if (!isset($_POST[$key]) || $_POST[$key] == '') {
			return $default;
		}
		
		if (is_array($_POST[$key]) && $clear == true) {
			$value = ($_POST[$key]);
			array_walk_recursive($value, array('KenRequest', 'clearInputValue'));
			return $value;
		} elseif ($clear == true) {
			return strip_tags($_POST[$key]);
		} else {
			return $_POST[$key];
		}
	}
	
	/**
	 * Russian:
	 * Получить значение типа Integer из массива POST
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function postInt($key, $default = 0)
	{
		if (!isset($_POST[$key]) || $_POST[$key] == '') {
			return $default;
		}
		
		return intval($_POST[$key]);
	}
	
	/**
	 * Russian:
	 * Получить значение типа Float из массива POST
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function postFloat($key, $default = 0.0)
	{
		if (!isset($_POST[$key]) || $_POST[$key] == '') {
			return $default;
		}
		
		return floatval($_POST[$key]);
	}
	
	/**
	 * English:
	 * Get data from GET or POST array
	 * 
	 * Russian:
	 * Получить переданный параметр из массива 
	 * GET или POST в зависимости от запроса
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 * @param unknown_type $clear
	 * 
	 */
	static public function query($key, $default = null, $clear=true ) {
		if (isset($_POST[$key]) && $_POST[$key] != '') {
			return self::post($key, $default, $clear);
		} elseif (isset($_GET[$key]) && $_GET[$key] != '') {
			return self::get($key, $default, $clear);
		}
		
		return $default;
	}
	
	/**
	 * Russian:
	 * Получить значение типа Integer из массива GET или POST
	 *  
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function queryInt($key, $default = 0)
	{
		if (isset($_POST[$key]) && $_POST[$key] != '') {
			return self::postInt($key, $default);
		} elseif (isset($_GET[$key]) && $_GET[$key] != '') {
			return self::getInt($key, $default);
		}
		
		return $default;
	}
	
	/**
	 * Russian:
	 * Получить значение типа Float из массива GET или POST
	 * 
	 * @param unknown_type $key
	 * @param mixed $default
	 */
	static public function queryFloat($key, $default = 0.0)
	{
		if (isset($_POST[$key]) && $_POST[$key] != '') {
			return self::postFloat($key, $default);
		} elseif (isset($_GET[$key]) && $_GET[$key] != '') {
			return self::getFloat($key, $default);
		}
		
		return $default;
	}
	
	/**
	 * Russian:
	 * Получить параметр из массива SERVER
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $default
	 */
	static public function server($key, $default=null)
	{
		$key = strtoupper($key);
		
		if (!isset($_SERVER[$key])) {
			return $default;
		}
		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}
	
	/**
	 * English:
	 * Get value from COOKIE array
	 * 
	 * Russian:
	 * Получить переданный параметр из массива COOKIE
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $clear
	 */
	static public function cookie($key, $clear=true) {
		if (!isset($_COOKIE[$key]) || $_COOKIE[$key] == '') {
			return null;
		}
		
		if (is_array($_COOKIE[$key]) && $clear == true) {
			$value = ($_COOKIE[$key]);
			array_walk_recursive($value, array('KenRequest', 'clearInputValue'));
			return $value;
		} elseif ($clear == true) {
			return strip_tags($_COOKIE[$key]);
		} else {
			return $_COOKIE[$key];
		}
	}

    static function clearInputValue($item)
    {
	    $test = strip_tags($item);
        return $test;
    }

    

}
