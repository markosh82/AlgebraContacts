<?php

require_once 'core/init.php';
    $user = new User();
    if(!$user->check()) {
        Redirect::to('index');
    }
?>
<h1> Dashboard</h1>