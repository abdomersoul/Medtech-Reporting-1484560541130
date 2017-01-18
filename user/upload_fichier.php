<?php
	$Auth->allow("upload_fichier");

	$con = new connexion();
?>


<?php

	$day=date("d");
	$month=date("m");
	$year=date("Y");
	
	$reporting_month=date("m",strtotime("-1 month"));
	$reporting_year=date("Y",strtotime("-1 month"));
	
	$day_before_2days = $day-2;
	$date_before_2days = $year."-".$month."-".$day_before_2days." ".date("H:i:s");
	
	$con = new connexion();
	
	$profils = $con->query("SELECT * FROM demande_reouverture WHERE reponse=0 AND date_activation >= '".$date_before_2days."' AND id_user=".$_SESSION["Auth"]->id)->fetchAll();
	
	$date=$year."-".$month."-".$day;
	$date_min=$year."-".$month."-1";
	$date_max=$year."-".$month."-15";
	
	$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$reporting_year."' AND mois='".$reporting_month."'")->fetch();
	
	
	
	if(isset($id_mois_requete) && !empty($id_mois_requete))
	{
		$id_mois = $id_mois_requete->id;
		//echo "id_mois = ".$id_mois."<br>";
	}
	else
	{
		$con->query("INSERT INTO mois (id,annee,mois,is_validated) VALUES ('','".$reporting_year."','".$reporting_month."','')");
		//echo "mois ".$year."-".$month." created<br>";
		
		$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$reporting_year."' AND mois='".$reporting_month."'")->fetch();
		$id_mois = $id_mois_requete->id;
		//echo "id_mois = ".$id_mois."<br>";
		
	}
		
	if(!empty($_POST))
	{
		extract($_POST);
		
		if (isset($action_demande) && $action_demande == 'add') 
		{
			$date_demande = date("Y")."-".date("m")."-".date("d")." ".date("H:i:s");
			$cont = $con->query("INSERT INTO demande_reouverture VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,$date_demande,'',$message,'-1']);
			
			$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,'Demande de reouverture de session',date("y-m-d"),1]);
				
			echo 'add demande';
		}
		
		else
		{
		
			$target_dir = "files/".$reporting_year."/".$reporting_month."/";
			if(!file_exists($target_dir))
			{
				mkdir($target_dir, 0755, true);
			}
			
			$target_file = $target_dir .$_SESSION["Auth"]->id."_". basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
			// Check if file already exists
			if (file_exists($target_file)) 
			{
				echo "Sorry, file already exists.<br>";
				$uploadOk = 0;
			}
			

			// Allow certain file formats
			if($FileType != "xlsm" && $FileType != "xlsx" && $FileType != "xlt" && $FileType != "jpg" && $FileType != "jpeg" && $FileType != "png" && $FileType != "pdf" && $FileType != "doc" && $FileType != "docx" )		
			{
				echo "Sorry, only MS Excel files, JPG, JPEG, PNG & PDF files are allowed.<br>";
				$uploadOk = 0;
			}
			
			
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 1000000) 
			{
				echo "Sorry, your file is too large.<br>";
				$uploadOk = 0;
			}
			
			if(isset($action) && $action == 'xsl')
			$requete_num_fichier = $con->query("SELECT COUNT(*) as num FROM files WHERE type='XSL' AND date_envoi >='".$date_min."' AND date_envoi <= '".$date_max."' 
												AND id_user=".$_SESSION["Auth"]->id)->fetch();
												
			if(isset($action) && $action == 'doc')
			$requete_num_fichier = $con->query("SELECT COUNT(*) as num FROM files WHERE type='DOC' AND date_envoi >='".$date_min."' AND date_envoi <= '".$date_max."' 
												AND id_user=".$_SESSION["Auth"]->id)->fetch();
		
			$num_fichier = $requete_num_fichier->num;
			// Check number of uploided files this month
			if ($num_fichier>10)
			{
				echo "Sorry, you already uploaded ".$num_fichier.".<br>";
				$uploadOk = 0;
			}
			
			// Check if date expired or a permission exists
			$test=14;
			if($uploadOk ==1 && $test>15)
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
					
					if(isset($id_file) && !empty($id_file))
					{
						echo "file id = ".$id_file."<br>";
						$cont = $con->query("UPDATE files SET path='".$target_file."' ,filename='".basename($_FILES["fileToUpload"]["name"])."', date_envoi='".date("Y-m-d")."' WHERE id=".$id_file);
					}
					else
					{
						echo "file id = X<br>";
						
						if(isset($action) && $action == 'xsl')
						$cont = $con->query("INSERT INTO files VALUES(?,?,?,?,?,?,?)",['',basename( $_FILES["fileToUpload"]["name"]),$target_file,date("Y-m-d"),"XSL",$_SESSION["Auth"]->id,$id_mois]);
						
						if(isset($action) && $action == 'doc')
						$cont = $con->query("INSERT INTO files VALUES(?,?,?,?,?,?,?)",['',basename( $_FILES["fileToUpload"]["name"]),$target_file,date("Y-m-d"),"DOC",$_SESSION["Auth"]->id,$id_mois]);
					}
					
					
					
					$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,"l'utilisateur à Uploader un fichier",date("y-m-d"),1]);
					
					require("inc/send_mail.php");
					$sujet= "Sujet : L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à envoyee un fichier";
					$body = "Salam,<br><br>L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à envoyee un fichier le ".date("Y-m-d").".<br><br>Cordialement.";
					send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);
					
					if ($uploadOk == 2) 
					{
						$con = new connexion();
						$cont = $con->query("UPDATE demande_reouverture SET reponse=2 WHERE id='".$profils[0]->id."'");
						echo "You just used a Modification/Upload permission";
					}
				}
				else 
				{
					echo "Sorry, there was an error uploading your file.";
				}
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

<h2> <center>  <u>
Reporting du mois de <?php setlocale(LC_TIME,'fr_FR.utf8','fra'); echo utf8_encode(strftime("%B %Y",strtotime("-1 month") ))?>
</u> </center>   </h2>
<a href="#" class="add">Demander réouverture</a>

<div class="adduser form" style="left: 35%;width: 40%;margin: 106px auto;">
	<h2>Demander la réouverture de la session</h2>
	<span class="close"></span>
	<form id="form" method="post" action="/user/upload_fichier">
		<p><input type="hidden" name="uid" value=""></p>
		<p><textarea name="message" placeholder="message...." value="" rows="7" cols="75"></textarea></p>
		<p><input type="hidden" name="action_demande" id="action" value="add_demande"></p>
		<p class="submit"><input type="submit" id="btnsubmit" name="submit" value="demander"></p>
	</form>
</div>

<!--  form -->


 <!-- Liste -->
 <table>
	<tr>
		<td>
		<fieldset>
		<legend align=center>Fichier du reporting XSL</legend>
			<table>
				<thead>
					<tr>
						<td>Intitulé</td>
						<td>Date d'envoi</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
							$date_du=$year."-".$month."-1";
							$date_au=$year."-".$month."-31";
				
							$con = new connexion();
	
							$profil = $con->query("SELECT * FROM files WHERE type='XSL' AND id_user=".$_SESSION["Auth"]->id." AND date_envoi>='".$date_du."' AND date_envoi<='".$date_au."'")->fetch();
						?>
				
						<td> <?php if(isset($profil->filename))   echo $profil->filename;   else echo "X";?> </td>
						<td> <?php if(isset($profil->date_envoi)) echo $profil->date_envoi; else echo "X";?> </td>
					</tr>
				</tbody>
			</table>

			<table>
				<thead>
					<tr>
						<td>File</td>
					</tr>
				</thead>
				<tbody>
					<form action="/user/upload_fichier" method="post" enctype="multipart/form-data">
						<tr>
							<input type="hidden" name="id_file" value="<?php if(isset($profil->id)) echo $profil->id; ?>">
							<input type="hidden" name="action" value="xsl">
							<td> <input type="file" name="fileToUpload" id="fileToUpload"> </td>
						</tr>
						<tr>
							<td> <input type="submit" value="Upload file" name="submit"> </td>
						</tr>
					</form>
				</tbody>
			</table>
		</fieldset>
		</td>

		<td>
		<fieldset>
		<legend align=center>Fichier complément DOC (Optionnel)</legend>
			<table>
				<thead>
					<tr>
						<td>Intitulé</td>
						<td>Date d'envoi</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
							$date_du=$year."-".$month."-1";
							$date_au=$year."-".$month."-31";
				
							$con = new connexion();
	
							$profil = $con->query("SELECT * FROM files WHERE type='DOC' AND id_user=".$_SESSION["Auth"]->id." AND date_envoi>='".$date_du."' AND date_envoi<='".$date_au."'")->fetch();
						?>
				
						<td> <?php if(isset($profil->filename))   echo $profil->filename;   else echo "X";?> </td>
						<td> <?php if(isset($profil->date_envoi)) echo $profil->date_envoi; else echo "X";?> </td>
					</tr>
				</tbody>
			</table>
			
			<table>
				<thead>
					<tr>
						<td>File</td>
					</tr>
				</thead>
				<tbody>
					<form action="/user/upload_fichier" method="post" enctype="multipart/form-data">
						<tr>
							<input type="hidden" name="id_file" value="<?php if(isset($profil->id)) echo $profil->id; ?>">
							<input type="hidden" name="action" value="doc">
							<td> <input type="file" name="fileToUpload" id="fileToUpload"> </td>
						</tr>
						<tr>
							<td> <input type="submit" value="Upload file" name="submit"> </td>
						</tr>
					</form>
				</tbody>
			</table>
		</fieldset>
		</td>

	</tr>
</table>