<?php
	$Auth->allow("demande_reouverture_user");

	$con = new connexion();
	

	$profils = $con->query("SELECT * FROM profil")->fetchAll();
	$option ="";
	foreach ($profils as $profil) $option .= "<option value='$profil->id'>".$profil->libelle."</option>";


	if(!empty($_POST))
	{
		extract($_POST);
		if (isset($action) && $action == 'add') 
		{
			$date_demande = date("Y")."-".date("m")."-".date("d")." ".date("H:i:s");
			$cont = $con->query("INSERT INTO demande_reouverture VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,$date_demande,'',$message,'-1']);
			
			$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,'Demande de reouverture de session',date("y-m-d"),1]);
				
			echo 'add';
		}
	}

	$data = $con->query("SELECT * FROM demande_reouverture WHERE id_user=".$_SESSION["Auth"]->id)->fetchAll();

	//echo var_dump($data);

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

<h2> <center>  <u> Les demandes de réouvertures </u> </center>   </h2>
<a href="#" class="add">Demander réouverture</a>

<!--  form -->

<div class="adduser form" style="left: 35%;width: 40%;margin: 106px auto;">
      <h2>Demander la réouverture de la session</h2>
      <span class="close"></span>
      <form id="form" method="post" action="/user/demande_reouverture_user">
      	<p><input type="hidden" name="uid" value=""></p>
        <p><textarea name="message" placeholder="message...." value="" rows="7" cols="75"></textarea></p>
        <p><input type="hidden" name="action" id="action" value="add"></p>
        <p class="submit"><input type="submit" id="btnsubmit" name="submit" value="demander"></p>
      </form>
    </div>

 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>Message</td>
		<td>Réponse</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>">
	<td id="message" data-value="<?php echo $d->message ?>"><?php echo $d->message ?></td>
	<td id="reponse" data-value="<?php echo $d->reponse ?>">
	<?php 
	if($d->reponse == -1)
	echo "En cours";
	if($d->reponse == 0)
	echo "Acceptée";
	if($d->reponse == 1)
	echo "Refusée";
	if($d->reponse == 2)
	echo "Utilisée";
	?>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


