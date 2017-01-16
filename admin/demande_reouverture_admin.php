<?php
	$Auth->allow("demande_reouverture_admin");

	$con = new connexion();

	$date_demande_du = date("Y")."-".date("m")."-1 00:00:00";
	$date_demande_au = date("Y")."-".date("m")."-31 23:59:59";	

	if(!empty($_POST)){
		extract($_POST);
		if (isset($action) && $action == 'upd') 
		{
			$date_reponse = date("Y")."-".date("m")."-".date("d")." ".date("H:i:s");
			
			$cont = $con->query("UPDATE demande_reouverture SET reponse='".$reponse."', date_activation = '".$date_reponse."' WHERE id='".$id."'");
			
			$res = $con->query("SELECT id_user FROM demande_reouverture WHERE id=".$id)->fetch();
			
			$notification_action="Demande de reouverture ";
			if($reponse == -1)
			$notification_action.="En cours";
			if($reponse == 0)
			$notification_action.="Acceptée";
			if($reponse == 1)
			$notification_action.="Refusée";
			
			$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,$res->id_user,$notification_action,date("y-m-d"),1]);
		}
	}

	$data = $con->query("SELECT u.nom,u.prenom,d.reponse,d.message,d.id , d.date_demande FROM demande_reouverture d JOIN users u ON u.id=d.id_user 
	WHERE date_demande>='".$date_demande_du."' AND date_demande<='".$date_demande_au."' ORDER BY d.date_demande DESC ")->fetchAll();

	//echo var_dump($data);

?>

<script type="text/javascript">
$(function () {
	console.log("red");
	$("a.update").click(function(){

		$rowid = $(this).data('value');
		$("textarea[name='message']").val($("tr#"+$rowid+" td#message").data("value"));
		$("select[name='reponse']").val($("tr#"+$rowid+" td#reponse").data("value"));
		$("input[name='id']").val($("tr#"+$rowid).data("value"));

	});
});
</script>

<h2> <center>  <u> Les demandes de réouvertures </center>  </u></h2>

<!--  form -->

<div class="adduser form" style="left: 35%;width: 40%;margin: 106px auto;">
      <h2>Demander la réouverture de la session</h2>
      <span class="close"></span>
      <form id="form" method="post" action="/admin/demande_reouverture_admin">
      	<p><input type="hidden" name="id" value=""></p>
        <p><textarea name="message" rows="7" cols="75" disabled></textarea></p>
		<p align="center">
		<select name="reponse">
			<option value="-1">En cours</option>
			<option value="0">Accepter</option>
			<option value="1">Refuser</option>
			
		</select>
		</p>
        <p><input type="hidden" name="action" id="action" value="add"></p>
        <p class="submit"><input type="submit" id="btnsubmit" name="submit" value="demander"></p>
      </form>
    </div>

 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>Utilisateur</td>
		<td>Message</td>
		<td>Date de la demande</td>
		<td>Réponse</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>">
	<td id="user" data-value="<?php echo $d->nom." ".$d->prenom ?>"><?php echo $d->nom." ".$d->prenom ?></td>
	<td id="message" data-value="<?php echo $d->message ?>"><?php echo $d->message ?></td>
	<td id="date_demande" data-value="<?php echo $d->date_demande ?>"><?php echo $d->date_demande ?></td>
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
	<td>
		<a class="update" data-value="data-<?php echo $d->id ?>" href="#">Repondre</a>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


