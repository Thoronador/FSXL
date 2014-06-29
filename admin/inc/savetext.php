<?php

@session_start();

if ($_POST[text])
{
	$_SESSION[tmptext] = utf8_decode($_POST[text]);
}


?>