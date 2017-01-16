<?php

require("/inc/send_mail.php");

$month=date("m");
$year=date("y");

$con = new connexion();
$profils = $con->query("SELECT * FROM users WHERE id NOT IN (SELECT DISTINCT u.id FROM files f JOIN users u ON u.id=f.id_user WHERE f.date_envoi>='01-".$month."-".$year."') AND id_profil=2")->fetchAll();

foreach ($profils as $profil)
{
	$sujet= "Notification : Rappel d'envoi du rapport mensuel.";
	$body = "Salam M.".$profil->nom." ".$profil->prenom.",<br><br>Ceci est un rappel d'envoi du rapport mensuel pour l'email ".$profil->email.".<br><br>Cordialement.";

	send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);
}


?>