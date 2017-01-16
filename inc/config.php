<?php

try {
  $PDO = new PDO('mysql:host=localhost;dbname=qreport_db','root','');
  $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (Exception $e) {
  echo "Connexion impossible";
}

?>