<?php

echo'
			<div class="topmenuitem"><a href="../">'.$FS_PHRASES[topmenu_mainpage].'</a></div>
			<div class="trenner"></div>
			<div class="topmenuitem"><a href="index.php">'.$FS_PHRASES[topmenu_home].'</a></div>
			<div class="trenner"></div>
';

if ($_SESSION[loggedin])
{
	echo'
			<div class="topmenuitem"><a href="?mod=main&go=profil">'.$FS_PHRASES[topmenu_profile].'</a></div>
			<div class="trenner"></div>
	';

	if ($_SESSION[user]->access[main][module] || (in_array($_SESSION[user]->userid, $FSXL[superadmin])))
	{
		echo'
			<div class="topmenuitem"><a href="?mod=main&go=module">'.$FS_PHRASES[topmenu_mods].'</a></div>
			<div class="trenner"></div>
		';
	}
	if ($_SESSION[user]->access[main][phpinfo] || (in_array($_SESSION[user]->userid, $FSXL[superadmin])))
	{
		echo'
			<div class="topmenuitem"><a href="?mod=main&go=phpinfo">'.$FS_PHRASES[main_menu_phpinfo].'</a></div>
			<div class="trenner"></div>
		';
	}
	echo'
			<div class="topmenuitem" style="float:right;"><a href="../logout.php">'.$FS_PHRASES[topmenu_logout].'</a></div>
	';
}

?>