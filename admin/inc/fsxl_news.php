<?php

$code = implode('', file('http://stream.frogspawn.de/stream.php?host='.$_SERVER[HTTP_HOST].'&version='.$_POST[version]));
echo $code;

?>