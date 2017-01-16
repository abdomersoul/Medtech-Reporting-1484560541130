<?php
	$Auth->allow("etat_envoi");

	$con = new connexion();
	
	$jour=date("d");
	$mois=date("m");
	$annee=date("Y");
	$date_min=$annee."-".$mois."-1";
	$date_max=$annee."-".$mois."-31";
	
	$reporting_month=date("m",strtotime("-1 month"));
	$reporting_year=date("Y",strtotime("-1 month"));
	
	/* $data = $con->query("SELECT u.nom, u.prenom, u.id as id_user , f.id as id_file, f.path, f.filename, f.date_envoi ,f.type FROM users u 
	LEFT JOIN files f ON u.id=f.id_user  AND f.date_envoi>='".$date_min."' AND f.date_envoi<='".$date_max."'  WHERE u.id_profil=2")->fetchAll(); */
	
	$data = $con->query("SELECT u.nom, u.prenom, u.id as id_user , r.id as id_rapport, r.date_envoi , r.is_valider FROM users u 
	LEFT JOIN rapport r ON u.id=r.id_user  AND r.date_envoi>='".$date_min."' AND r.date_envoi<='".$date_max."'  WHERE u.id_profil=2")->fetchAll();
	
	$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$reporting_year."' AND mois='".$reporting_month."' AND is_validated=0 ")->fetch();
	
	if(!empty($_POST))
	{
		extract($_POST);
		if (isset($action) && $action == 'search') 
		{
			$reporting_month=$month;
			$reporting_year=$year;
			$date_du=$year."-".($month+1)."-1";
			$date_au=$year."-".($month+1)."-31";
			if($month==12)
			{
				$date_du=($year+1)."-1-1";
				$date_au=($year+1)."-1-31";
			}
			//$requete = "SELECT u.nom,u.prenom,f.filename,f.path,f.date_envoi,f.id FROM files f JOIN users u ON f.id_user=u.id WHERE u.id_profil=2 ";
			
			/* $requete = "SELECT u.nom, u.prenom, u.id as id_user , f.id as id_file, f.path, f.filename, f.date_envoi ,f.type FROM users u 
			LEFT JOIN files f ON u.id=f.id_user  AND f.date_envoi>='".$date_du."' AND f.date_envoi<='".$date_au."'  WHERE u.id_profil=2"; */
			
			$requete = "SELECT u.nom, u.prenom, u.id as id_user , r.id as id_rapport, r.date_envoi , r.is_valider FROM users u 
			LEFT JOIN rapport r ON u.id=r.id_user  AND r.date_envoi>='".$date_du."' AND r.date_envoi<='".$date_au."'  WHERE u.id_profil=2";
			
			$data = $con->query($requete)->fetchAll();
			$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$year."' AND mois='".$month."' AND is_validated=0 ")->fetch();
		}
		
		if (isset($action) && $action == 'valider') 
		{
			$test=13;
			if($jour<=15)
				echo "you can't validate a month before upload dead end.<br>";
			else
			{
				$con->query("UPDATE mois SET is_validated=1 WHERE annee='".$reporting_year."' AND mois='".$reporting_month."'");
				$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$reporting_year."' AND mois='".$reporting_month."' AND is_validated=0 ")->fetch();
			}
		}
		
		if (isset($action) && $action == 'valider_rapport') 
		{
			if(isset($_POST['Visualiser']))
			{
				$url="/admin/reporting_admin?id_rapport=".$id_rapport;
				header("Location: $url");
			}
			
			$notif = new notification();
			
			if(isset($_POST['Valider']))
			{
				$con->query("UPDATE rapport SET is_valider=1 WHERE id=".$id_rapport);
				$message = "le Rapport à été valider par l\'administrateur";
				$notif->send_notification($_SESSION["Auth"]->id,$id_user,$message,date('Y-m-d'));
			}
			if(isset($_POST['Refuser']))
			{
				$con->query("UPDATE rapport SET is_valider=2 WHERE id=".$id_rapport);
				$message = "le Rapport à été refuser par l\'administrateur";
				$notif->send_notification($_SESSION["Auth"]->id,$id_user,$message,date('Y-m-d'));
			}
				
			$data = $con->query("SELECT u.nom, u.prenom, u.id as id_user , r.id as id_rapport, r.date_envoi , r.is_valider FROM users u 
			LEFT JOIN rapport r ON u.id=r.id_user  AND r.date_envoi>='".$date_min."' AND r.date_envoi<='".$date_max."'  WHERE u.id_profil=2")->fetchAll();
		}
		
		if (isset($action) && $action == 'download') 
		{
			$getFile =new Download_file();
			$getFile->download($id_file);
		}
	}
	
	

?>

<script type="text/javascript">
$(function () {
	console.log("red");
	$("a.update").click(function(){

		
		
		$rowid = $(this).data('value');
		console.log("Rowid = "+$rowid);
		document.getElementById('frame').src = "../"+$("tr#"+$rowid+" td#path").data("value");
		console.log("tr#"+$rowid+" td#path");
		console.log("src = "+$("tr#"+$rowid+" td#path").data("value"));
		console.log($("input[name='uid']").val());

	});
});
</script>

<h2> <center>  <u> Reporting du mois de <?php setlocale(LC_TIME,'fr_FR.utf8','french'); echo utf8_encode(strftime("%B %Y",strtotime($reporting_year."-".$reporting_month."-1"))) ?>
	<?php
	if(isset($id_mois_requete) && !empty($id_mois_requete))
		echo " : Non Validé";
	else
		echo " : Validé";
	?>
</u> </center>   </h2>

<!--  form -->

<div class="adduser form" style="left: 25%;width: 59%;">
	<span class="close"></span>
	<iframe id="frame" name="pdf_frame" src="../files/rapport2.pdf" width="800" height="450" align="middle"></iframe>
</div>

<!-- Search form -->
<form id="form" method="post" action="/admin/etat_envoi">
	<table>
		<thead>
			<tr>
				<td>Année</td>
				<td>Mois</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<select id="year" name="year">
						<script>
							var myDate = new Date();
							var year = myDate.getFullYear();
							var report_year = "<?php echo $reporting_year ;?>";
							for(var i = 2016; i < year+1; i++)
							{
								if(report_year==i)
									document.write('<option value="'+i+'" selected>'+i+'</option>');
								else
									document.write('<option value="'+i+'">'+i+'</option>');
							}
						</script>
					</select>
				</td>
				<td>
					<select name="month">
					
						<option value="1" <?php if($reporting_month==1) echo"selected";?> >Janvier</option>
						<option value="2" <?php if($reporting_month==2) echo"selected";?> >Fevrier</option>
						<option value="3" <?php if($reporting_month==3) echo"selected";?> >Mars</option>
						<option value="4" <?php if($reporting_month==4) echo"selected";?> >Avril</option>
						<option value="5" <?php if($reporting_month==5) echo"selected";?> >Mai</option>
						<option value="6" <?php if($reporting_month==6) echo"selected";?> >Juin</option>
						<option value="7" <?php if($reporting_month==7) echo"selected";?> >Juillet</option>
						<option value="8" <?php if($reporting_month==8) echo"selected";?> >Aout</option>
						<option value="9" <?php if($reporting_month==9) echo"selected";?> >Septembre</option>
						<option value="10" <?php if($reporting_month==10) echo"selected";?> >Octobre</option>
						<option value="11" <?php if($reporting_month==11) echo"selected";?> >Novembre</option>
						<option value="12" <?php if($reporting_month==12) echo"selected";?> >Decembre</option>
					
					</select>
				</td>
				<input type="hidden" name="action" id="action" value="search">

				<td><input type="submit" value="Search" name="search"></input></td>
			</tr>
		</tbody>
	</table>
</form>
 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>Nom</td>
		<td>Prénom</td>
		<td>Date d'envoi</td>
		<td>Visualisation</td>
		<td>Action</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id_rapport; ?>" data-value="<?php echo $d->id_user ?>">
	<td id="nom" data-value="<?php echo $d->nom ?>"><?php echo $d->nom ?></td>
	<td id="prenom" data-value="<?php echo $d->prenom ?>"><?php echo $d->prenom ?></td>
	
	<?php  
	if($d->date_envoi=="")
	{
		$d->date_envoi="X";
	}
	?>
	<td id="date_envoi" data-value="<?php echo $d->date_envoi ?>"><?php echo $d->date_envoi ?></td>
	<?php if($d->date_envoi!="X"): ?>
	<td>
		<!-- <a class="update" data-value="data-<?php echo $d->id_rapport ?>" href="#">Visualiser</a>
		<a data-value="data-<?php echo $d->path ?>" href="<?php echo '../'.$d->path ?>" download>Telecharger</a> -->
		
		<form method="post" action="/admin/etat_envoi">
		<input type="hidden" name="id_rapport" value="<?php echo $d->id_rapport ?>" />
		<input type="hidden" name="id_user" value="<?php echo $d->id_user ?>" />
		<input type="hidden" name="action" value="valider_rapport" />
		<!-- <input type="submit" name="telecharger" value="Telecharger"/> -->
		<input type="submit" name="Visualiser" value="Visualiser"/>
	</td>
	<td>
		<?php if($d->is_valider=="0"): ?>
			<input style="background : #0088cc;" type="submit" name="Valider" value="Valider"/>
			<input style="background : #d44937 ;" type="submit" name="Refuser" value="Refuser"/>
		<?php endif; ?>
		<?php if($d->is_valider=="1"): ?>
			<p style="font-weight: bold;font-size: 15px;" > Rapport Validé </p>
		<?php endif; ?>
		<?php if($d->is_valider=="2"): ?>
			<p style="font-weight: bold;color: #d44937;font-size: 15px;" > Rapport Refusé </p>
		<?php endif; ?>
		</form>
	</td>
	<?php endif; ?>
	
	
	</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if(isset($id_mois_requete) && !empty($id_mois_requete)) :  ?>

<form method="post" action="/admin/etat_envoi">
<input type="hidden" name="action" value="valider"/>

<p align="center"> <input type="submit" name="submit" value="Valider le mois"/> </p>

<?php endif ?>

</form>
