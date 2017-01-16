<?php
	$Auth->allow("update_file");

	$con = new connexion();
?>


<?php

	$day=date("d");
	$month=date("m");
	$year=date("Y");
	
	$day_before_2days = $day-2;
	$date_before_2days = $year."-".$month."-".$day_before_2days." ".date("H:i:s");
	
	$con = new connexion();
	
	$profils = $con->query("SELECT * FROM demande_reouverture WHERE reponse=0 AND date_activation >= '".$date_before_2days."' AND id_user=".$_SESSION["Auth"]->id)->fetchAll();
	
	$date=$year."-".$month."-".$day;
	$date_min=$year."-".$month."-1";
	$date_max=$year."-".$month."-15";
	
	$requete_num_fichier = $con->query("SELECT COUNT(*) as num FROM files WHERE date_envoi >='".$date_min."' AND date_envoi <= '".$date_max."' 
											AND id_user=".$_SESSION["Auth"]->id)->fetch();
	
	$num_fichier = $requete_num_fichier->num;
	
	/* echo "date_min = ".$date_min."<br>";
	echo "date_max = ".$date_max."<br>";
	echo "num fichier = ".$num_fichier."<br>";  */
	
	if(!empty($_GET))
	{
		extract($_GET);
	}
	
	if(!empty($_POST))
	{
		extract($_POST);
		
		
		$target_dir = "files/".$year."/".$month."/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Check if file already exists
		if (file_exists($target_file)) 
		{
			echo "Sorry, file already exists.<br>";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		if($FileType != "xlsm" && $FileType != "xlsx" && $FileType != "xlt" && $FileType != "jpg" && $FileType != "jpeg" && $FileType != "png" && $FileType != "pdf" )		
		{
			echo "Sorry, only MS Excel files, JPG, JPEG, PNG & PDF files are allowed.<br>";
			$uploadOk = 0;
		}
		
		
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 10000000) 
		{
			echo "Sorry, your file is too large.<br>";
			$uploadOk = 0;
		}
		
		// Check if date expired or a permission exists
		if($uploadOk ==1 && $year>15)
		{
			if($profils!=null)
			{
				echo "There is a positive response.<br>";
				$uploadOk = 2;
			}
			else
			{
				echo "Sorry, time expired.<br>";
				$uploadOk = 0;
			}
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) 
		{
			echo "Sorry, your file was not uploaded.";
		}
		
		else 
		{
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
			{
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
				$con = new connexion();
				
				$cont = $con->query("UPDATE files SET path='".$target_file."' ,filename='".basename($_FILES["fileToUpload"]["name"])."', date_envoi='".date("Y-m-d")."' WHERE id=".$file_id);
				
				$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,"l'utilisateur à modifier un fichier",date("y-m-d"),1]);
				
				require("/inc/send_mail.php");
				$sujet= "Sujet : L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à envoyee un fichier";
				$body = "Salam,<br><br>L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à Modifier un fichier le ".date("Y-m-d").".<br><br>Cordialement.";
				send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);
				
				
				if ($uploadOk == 2) 
				{
					$con = new connexion();
					$cont = $con->query("UPDATE demande_reouverture SET reponse=2 WHERE id='".$profils[0]->id."'");
					echo "You just used a permission";
				}
			}
			else 
			{
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
?>

<script type="text/javascript">
$(function () {
	console.log("red");
	$("a.update").click(function(){

		$rowid = $(this).data('value');
		$("input[name='nom']").val($("tr#"+$rowid+" td#nom").data("value"));
		$("input[name='prenom']").val($("tr#"+$rowid+" td#prenom").data("value"));
		$("input[name='email']").val($("tr#"+$rowid+" td#email").data("value"));
		$("input[name='username']").val($("tr#"+$rowid+" td#username").data("value"));
		$("select[name='profil']").val($("tr#"+$rowid+" td#profil").data("value"));
		$("input[name='uid']").val($("tr#"+$rowid).data("value"));
		console.log($("input[name='uid']").val());

	});
});
</script>

<h2>Upload fichier</h2>

<!--  form -->


 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>File</td>
		</tr>
	</thead>
	<tbody>
		<form action="/user/update_file" method="post" enctype="multipart/form-data">
			<tr>
			<td> <input type="file" name="fileToUpload" id="fileToUpload"> </td>
			<input type="hidden" name="file_id" value="<?php echo $file_id;?>"/>
			</tr>
			<tr>
			<td> <input type="submit" value="Upload Image" name="submit"> </td>
			</tr>
		</form>
	</tbody>
</table>