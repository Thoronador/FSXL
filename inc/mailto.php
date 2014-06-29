<?php

$link = explode('§§', $_GET[link]);
header("Location: mailto:$link[2]@$link[1].$link[0]");

echo'<script type="text/javascript">window.close();</script>';

?>