<?php

class Helper 
{
	private static $_config;
	
	private function __construct(){
		
		self::$_config = Config::get('config/app');		
	}
	
	private function __clone(){}
	
	public static function getHeader ($title, $path = 'header')
	{
		if ($path) {
			
			$file = require_once 'includes/layout/' . $path . '.php';
			
			return $file;	
		}
		return false;
		
	}
	
	public static function getFooter ($path = 'footer')
	{
	if ($path) 
    {
		$file = require_once 'includes/layout/' . $path . '.php';
		return $file;
	}	
	return false;	
		
	}
	
}