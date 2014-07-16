<?php

	@setcookie('username');
	@setcookie('password');
	@session_start();

	include("admin/inc/functions.inc.php");

	$page = $_SESSION[currentpage];
	session_start();
	session_destroy();

	reloadPage();

?>