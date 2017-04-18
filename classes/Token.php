<?php
class Token
{
	private static $_config;
	
	private function __construct()
	{
		self::$_config = Config::get('config/session');
	}
	
	
	
	public static function generate()
	{
		return Session::put(self::$_config['sessions']['token_name'], md5(uniqid()));
	}
	
	public static function check($token)
	{
		 $token_name = self::$_config['sessions']['token_name'];
		
		if(Session::exists($token_name) && $token === Session::get($token_name)) {
            Session::delete($token_name);
            return true;
		}
		
		return false;
	}
}