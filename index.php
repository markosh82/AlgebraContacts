<?php

require_once 'core/init.php';


Helper::getHeader('Algebra Contacts');
$db = DB::getInstance()->get('*', 'users');
//$db = DB::getInstance()->query("SELECT * FROM users WHERE id=? AND name=?", array(3,'Pero'));
//$db = DB::getInstance()->delete('users', array('id', '=', '4'));
echo '<pre>';
var_dump($db);
/*if ($db->count() > 0) {
	foreach ($db->results() as $result) {
	echo $result->name;
}
}
    else {
		echo 'Trenutno nema podataka u bazi!!!!';
	}
	
DB::getInstance()->get('*', 'users'); */
	
/*DB::getInstance()->insert('users', array(
'username'=>'Marko',
'password'=>'123456789',
'salt'=>'65+464sdfg',
'name'=>'Marko Markic',
'role_id'=>1));	*/

//"INSERT INTO users (name, username, password, salt) VALUES(?,?,?,?)"

/*DB::getInstance()->update('users', 7, array(
'name'=>'Iva Ivić',
'username'=>'Iva'
)); */

//"UPDATE users SET name=?, username=? WHERE id=7"
	
?>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="jumbotron">
					<div class="container">
						<h1>Algebra Contacts</h1>
						<p>Lorem ipsum dolor sit amet,
						consectetur adipiscing elit. Morbi
						et dolor sapien. Morbi faucibus,
						lacus a ornare finibus, justo nisl
						interdum turpis, et ornare diam
						libero eget leo.</p>
						<p>
							<a class="btn btn-primary btn-lg"
							href="login.php" role="button">
							Sign in</a>
							or
							<a class="btn btn-primary btn-lg"
							href="register.php" role=
							"button">Create an acount</a>
						</p>
					</div>
				</div>
			</div>
		</div>

   

    
	
<?php


Helper::getFooter();

?>