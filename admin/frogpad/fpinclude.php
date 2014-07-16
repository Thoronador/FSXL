<?php
// 'MSIE 7.0', 
$validbrowser = array('Firefox/2.0', 'Firefox/3.0', 'MSIE 7.0', 'Opera/9.5', 'Safari');
foreach($validbrowser AS $browser)
{
	if (strstr($_SERVER['HTTP_USER_AGENT'], $browser))
	{
		$openbrowser = true;
	}
}
if ($openbrowser)
{
	$FSXL[content] .= '<script type="text/javascript">createWysiwyg();</script>';
}
else
{
	$FSXL[content] .= '<script type="text/javascript">createWysiwyg_soft();</script>';
}

?>