<?php
class Token
{
	private static $_token_name;
	
	private function __construct()
	{
		self::$_token_name = Config::get('config/session')['sessions']['token_name'];
	}
	
	public static function getInstance()
	{
		return new Token();
	}
	
	public function generate()
	{
		return Session::put(self::$_token_name, hash('sha256', uniqid()));
	}
	
	public function check($token)
	{
		if(Session::exists(self::$_token_name) && $token === Session::get(self::$_token_name)) {
			Session::delete(self::$_token_name);
			return true;
		}
		
		return false;
	}
}