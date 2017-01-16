<div class="mainmenu">
<span>Menu</span>
<ul>
<?php foreach($_SESSION["Auth"]->actions[0] as $key => $val): ?>

	<?php if($val->action == 'gestion_des_utilisateurs'):?>
	<li><a href="/admin/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'upload_fichier_admin'):?>
	<li><a href="/admin/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>

	<?php if($val->action == 'demande_reouverture_admin'):?>
	<li><a href="/admin/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>

	<?php if($val->action == 'fichier_recu_admin'):?>
	<li><a href="/admin/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'etat_envoi'):?>
	<li><a href="/admin/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'upload_fichier'):?>
	<li><a href="/user/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'demande_reouverture_user'):?>
	<li><a href="/user/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'fichier_envoyee'):?>
	<li><a href="/user/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'reporting_archive'):?>
	<li><a href="/user/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'reporting'):?>
	<li><a href="/user/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	
	
	<?php if($val->action == 'etat_envoi_SV'):?>
	<li><a href="/admin_sans_validation/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	<?php if($val->action == 'etat_envoi_AV'):?>
	<li><a href="/admin_avec_validation/<?php echo $val->action; ?>"><?php echo str_replace('_',' ',$val->action); ?></a></li>
	<?php endif; ?>
	
	
	
	
	
	

	
<?php endforeach; ?>
</ul>

</div>




















