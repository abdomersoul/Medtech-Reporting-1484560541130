<?php
require("send_mail.php");
require("connexion.php");

$month=date("m");
$year=date("y");

$con = new connexion();
$profils = $con->query("SELECT * FROM users WHERE id NOT IN (SELECT DISTINCT u.id FROM files f JOIN users u ON u.id=f.id_user WHERE f.date_envoi>='".$year."-".$month."-01')")->fetchAll();

/*
foreach ($profils as $profil)
{
	$sujet= "Notification : Rappel d'envoi du rapport mensuel.";
	$body = "Salam M.".$profil->nom." ".$profil->prenom.",<br><br>Ceci est un rappel d'envoi du rapport mensuel pour l'email ".$profil->email.".<br><br>Cordialement.";

	send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);
}
*/

$sujet= "Notification : Rappel d'envoi du rapport mensuel.";
$body="";
foreach ($profils as $profil)
{
	$body .= "Salam M.".$profil->nom." ".$profil->prenom.",<br><br>Ceci est un rappel d'envoi du rapport mensuel pour l'email ".$profil->email.".<br><br>Cordialement.";
}
send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);

/*
$file=dirname(__file__).'/output.txt';

$data="hello, it's ".date('d-m-y H:i:s')."\n";

file_put_contents($file,$data,FILE_APPEND);
*/

?>