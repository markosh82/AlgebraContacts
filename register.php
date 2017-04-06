<?php 

require_once 'core/init.php';


Helper::getHeader('Algebra Contacts');

if (Input::exists()) {
	//echo 'RADI';
	//echo Input::get('name');
}

?>

<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Create an account</h3>
			</div>
			<div class="panel-body">
				<form method="post">
					<div class="form-group">
						<label for="name" class="control-label">Name*</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="<?php echo escape(Input::get('name'))?>">
					</div>
					<div class="form-group">
						<label for="username" class="control-label">Username*</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
					</div>
					<div class="form-group">
						<label for="password" class="control-label">Password*</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Choose a password">
					</div>
					<div class="form-group">
						<label for="password_again" class="control-label">Confirm password*</label>
						<input type="password" class="form-control" id="password_again" name="password_again" placeholder="Enter your password again">
					</div>
					<div class="form-group">
					<button type="submit" class="btn btn-primary">Create an account</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<?php


Helper::getFooter();

?>