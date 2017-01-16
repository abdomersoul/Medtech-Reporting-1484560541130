<?php
	$Auth->allow("reporting_archive");
	
	$jour=date("d");
	$mois=date("m");
	$annee=date("Y");
	
	$reporting_month=date("m",strtotime("-1 month"));
	$reporting_year=date("Y",strtotime("-1 month"));

	$con = new connexion();
	
	$data = $con->query("SELECT u.nom,u.prenom,r.date_envoi,r.id FROM rapport r JOIN users u ON r.id_user=u.id AND r.id_user='".$_SESSION["Auth"]->id."' ORDER BY r.date_envoi DESC")->fetchAll();
	
	if(!empty($_POST)){
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
			$requete = "SELECT r.date_envoi,r.id FROM rapport r JOIN users u ON r.id_user=u.id WHERE r.id_user='".$_SESSION["Auth"]->id."' ";
			if(isset($date_du) && !empty($date_du))
				$requete.=" AND date_envoi >= '".$date_du."'";
			if(isset($date_au) && !empty($date_au))
				$requete.=" AND date_envoi <= '".$date_au."'";
			$requete.=" ORDER BY r.date_envoi DESC";
			$data = $con->query($requete)->fetchAll();
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

<h2> <center>  <u> Archive de Reporting </u> </center>   </h2>

<!--  form -->




<!-- Search form -->
<form id="form" method="post" action="/user/reporting_archive">
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
		<td>Date d'envoi</td>
		<td>Opérations</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>">
	<td id="date_envoi" data-value="<?php echo $d->date_envoi ?>"><?php echo $d->date_envoi ?></td>
	<td>
		<a href="/user/reporting_user?id_rapport=<?php echo $d->id; ?>">Visualiser</a>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>

