<?php

	$con = new connexion();
	

	$data = $con->query("SELECT n.id_user, n.action, n.id, n.date_action, n.is_new_notification, u.nom,u.prenom FROM notification n JOIN users u ON n.id_user=u.id WHERE n.id_user_notification_for='".$_SESSION["Auth"]->id."' ORDER BY n.date_action DESC")->fetchAll();
	
	$res = $con->query("UPDATE `notification` SET `is_new_notification` = 0 WHERE id_user_notification_for = '".$_SESSION["Auth"]->id."'");
	
	//echo var_dump($data);

?>


<h2>Les demandes de reouvertures</h2>

<!--  form -->

 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>Utilisateur</td>
		<td>Action</td>
		<td>Date d'action
		<td>is new notification ?</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>" <?php if($d->is_new_notification == 1) echo "style=' background:#edf2fa; '"; ?> > 
	<td id="user" data-value="<?php echo $d->nom." ".$d->prenom ?>"><?php echo $d->nom." ".$d->prenom ?></td>
	<td id="action" data-value="<?php echo $d->action ?>"><?php echo $d->action ?></td>
	<td id="date_action" data-value="<?php echo $d->date_action ?>"><?php echo $d->date_action ?></td>
	<td id="is_new_notification" data-value="<?php echo $d->is_new_notification ?>">
	<?php 
	if($d->is_new_notification == 1)
	echo "New Notification";
	if($d->is_new_notification == 0)
	echo "Old Notification";
	?>
	</td>
	</tr>
	
<?php endforeach; ?>
</tbody>
</table>


