<?php

	@setcookie('username');
	@setcookie('password');

	include("inc/functions.inc.php");

	session_start();
	session_destroy();
	unset($_SESSION);

	reloadPage();

?>