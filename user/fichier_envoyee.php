<?php
	$Auth->allow("fichier_envoyee");
	
	$jour=date("d");
	$mois=date("m");
	$annee=date("Y");
	
	$reporting_month=date("m",strtotime("-1 month"));
	$reporting_year=date("Y",strtotime("-1 month"));

	$con = new connexion();
	
	$data = $con->query("SELECT u.nom,u.prenom,f.filename,f.path,f.date_envoi,f.id FROM files f JOIN users u ON f.id_user=u.id AND f.id_user='".$_SESSION["Auth"]->id."' ORDER BY f.date_envoi DESC")->fetchAll();
	
	if(!empty($_POST))
	{
		extract($_POST);
		if (isset($action) && $action == 'search')
		{
			$reporting_month=$month;
			$reporting_year=$year;
			$search_month=$month+1;
			$searh_year=$year;
			if($search_month==13)
			{
				$search_month=1;
				$searh_year=$year+1;
			}
			$date_du=$searh_year."-".$search_month."-1";
			$date_au=$searh_year."-".$search_month."-31";
			$requete = "SELECT f.filename,f.path,f.date_envoi,f.id FROM files f JOIN users u ON f.id_user=u.id WHERE f.id_user='".$_SESSION["Auth"]->id."' ";
			if(isset($date_du) && !empty($date_du))
				$requete.=" AND date_envoi >= '".$date_du."'";
			if(isset($date_au) && !empty($date_au))
				$requete.=" AND date_envoi <= '".$date_au."'";
			if(isset($user) && !empty($user))
				$requete.=" AND u.id='".$user."'";
			$requete.=" ORDER BY f.date_envoi DESC";
			$data = $con->query($requete)->fetchAll();
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
		document.getElementById('frame').src = "../"+$("tr#"+$rowid+" td#path").data("value");
		console.log($("input[name='uid']").val());

	});
});
</script>

<h2> <center>  <u> Fichiers Envoyée </u> </center>   </h2>

<!--  form -->

<div class="adduser form" style="left: 25%;width: 59%;">
	<span class="close"></span>
	<iframe id="frame" name="pdf_frame" src="../files/rapport2.pdf" width="800" height="450" align="middle"></iframe>
</div>



<!-- Search form -->
<form id="form" method="post" action="/user/fichier_envoyee">
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
		<td>intitulé</td>
		<td>Date d'envoi</td>
		<td>Opérations</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>">
	<td id="path" data-value="<?php echo $d->path ?>"><?php echo $d->filename ?></td>
	<td id="date_envoi" data-value="<?php echo $d->date_envoi ?>"><?php echo $d->date_envoi ?></td>
	<td>
		<!--  class="update" data-value="data-<?php echo $d->id ?>" href="#">Visualiser</a> -->
		<form method="post" action="/user/fichier_envoyee">
			<input type="hidden" name="id_file" value="<?php echo $d->id ?>" />
			<input type="hidden" name="action" value="download" />
			<input type="submit" name="telecharger" value="Telecharger"/>
		</form>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>

