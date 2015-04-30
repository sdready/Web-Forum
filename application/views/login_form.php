<style>
	#page {
		width:100%;
	}
</style>

<div id="login_form">
	<h1>Login</h2>
	<?php

	echo form_open('login/validate');
	echo form_input('username', 'Username');
	echo form_password('password', 'Password');
	echo form_submit('submit', 'Login');

	echo anchor('login/signup', 'Create Account');

	?>
</div>