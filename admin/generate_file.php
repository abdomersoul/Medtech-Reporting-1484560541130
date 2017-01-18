<?php

if(!empty($_POST))
{
	extract($_POST);
	
	require "inc/class.excel_generation.php";
	$genarator = new excel_generation();
	$genarator->generate($rapport_id);
	echo "Excel file have been generated";
	header("Location : /admin/etat_envoi");
}
else
	echo "Exceptions";

header("Location : /admin/etat_envoi");

?>