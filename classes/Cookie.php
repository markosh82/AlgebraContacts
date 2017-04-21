<?php

class Cookie {
	
	
	private function __construct(){}
	private function __clone(){}
	
	public static function exists($name) {
		return (isset($_COOKIE[$name])) ? true : false;
	}
	public static function get($name) {
		return $_COOKIE[$name];
	}
	public static function put($name, $value, $expire) {
		if(setcookie($name, $value, time() + $expire, '/', null, null, true)) {
			return true;
		}
		return false;
	}
	public static function delete($name) {
		self::put($name, '', time() -1);
	}
}