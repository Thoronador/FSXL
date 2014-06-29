<?php

$FSXL[title] = $FS_PHRASES[main_phpinfo_title];
$FSXL[content] = '';

ob_start();
phpinfo();

$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
foreach($matches as $match)
{
	if(strlen($match[1])) $phpinfo[$match[1]] = array();
	elseif(isset($match[3])) $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
	else $phpinfo[end(array_keys($phpinfo))][] = $match[2];
}

$i=0;
foreach($phpinfo as $name => $section)
{
	if ($name != 'standard')
	{
		$FSXL[content] .= '
			<table border="0" cellpading="2" cellspacing="1" style="width:95%; margin:0px auto; table-layout:fixed;">
				<tr>
					<td colspan="3">&nbsp;<br><b>'.$name.'</b><hr></td>
				</tr>
		';
		foreach($section as $key => $val)
		{
			if(is_array($val))
			{
				$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'">'.$key.'</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'">'.$val[0].'</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'">'.$val[1].'</td>
					</tr>
				';
			}
			elseif(is_string($key))
			{
				$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'">'.$key.'</td>
						<td colspan="2" class="alt'.($i%2 == 0 ? 1 : 2).'">'.$val.'</td>
					</tr>
				';
			}
			else $FSXL[content] .= '
					<tr>
						<td colspan="3" class="alt'.($i%2 == 0 ? 1 : 2).'">'.$val.'</td>
					</tr>
			';
			$i++;
		}
		$FSXL[content] .= '</table>';
	}
}

?>
