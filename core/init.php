<?php 

error_reporting(E_ALL);
ini_set('display_errors',TRUE);
ini_set('display_startup_errors',TRUE);

session_start();

spl_autoload_register(function ($class) {
    require_once 'classes/' . $class . '.php';
});
require_once 'functions/sanitize.php';



if(Cookie::exists($this->_cookieName) && !Session::exists($this->_sessionName)) {
	
	$hash = Cookie::get($this->_cookieName);
	$hashCheck = DB::getInstance()->get('hash', 'sessions', array('hash', '=', $hash));
	
	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}