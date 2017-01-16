<?php

session_start();

require "inc/connexion.php";

require "inc/class.auth.php";

require "inc/class.download.php";

require "inc/class.notification.php";

if (!isset($_GET["p"])){ $_GET["p"] = 'login';}
if (!file_exists($_GET["p"].'.php')) { $_GET["p"] = '404';}
  
ob_start();     
include $_GET["p"].'.php';
$content =  ob_get_contents();
ob_end_clean();


include "inc/theme.php";

?>
