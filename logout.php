<?php

require_once 'core/init.php';

$user = new User();

if($user->check()) {
	
	$user->logout();
}

Redirect::to('index');