<?php

@include("config.inc.php");
@include("functions.inc.php");
@include("class.inc.php");

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);

if (strlen($_POST[name]) > 2)
{
	$_POST[name] = str_replace("u|n|d", "&", $_POST[name]);

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `name` LIKE '%$_POST[name]%'");
	if (mysql_num_rows($index) > 0)
	{
		while ($userdata = mysql_fetch_assoc($index))
		{
			$userdata[name] = str_replace('"', '&quot;', $userdata[name]);
			$userdata[name] = str_replace("'", '&39;', $userdata[name]);
			echo '<div unselectable="on" class="dropdownItem" onClick="javascript:document.getElementById(\'username\').value=\''.$userdata[name].'\'; document.getElementById(\'userid\').value=\''.$userdata[id].'\'; closeUserDrop();">'.$userdata[name].'</div>';
		}
	}
	else
	{
		echo'not found';
	}
}


// Datenbank Verbindung schließen
$db->close();

?>