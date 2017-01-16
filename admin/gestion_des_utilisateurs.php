<?php
	$Auth->allow("gestion_des_utilisateurs");

	$con = new connexion();
	

	$profils = $con->query("SELECT * FROM profil")->fetchAll();
	$option ="";
	foreach ($profils as $profil) $option .= "<option value='$profil->id'>".$profil->libelle."</option>";

			

	if(!empty($_POST)){
		extract($_POST);
		if (isset($action) && $action == 'add') {
			$cont = $con->query("INSERT INTO users VALUES(?,?,?,?,?,?,?)",
				['',$nom,$prenom,$email,$username,md5($password),$profil]);
			echo 'add';
		}

		if (isset($action) && $action == 'upd') {
			if (isset($password) && !empty($password))
				$cont = $con->query("UPDATE users SET nom=?, prenom=?, email=?, username=?, password=?, id_profil=? WHERE id=?",
				[$nom,$prenom,$email,$username,md5($password),$profil,$uid]);
			else
			$cont = $con->query("UPDATE users SET nom=?, prenom=?, email=?, username=?, id_profil=? WHERE id=?",
				[$nom,$prenom,$email,$username,$profil,$uid]);

			echo 'update';
		}
	}
	
	if(!empty($_GET)){
		extract($_GET);
		if (isset($act) && $act == 'del') {
				$cont = $con->query("Delete FROM users where id=?",[$uid]);
				echo 'delete';
			}
	}

	$data = $con->query("SELECT u.*,p.libelle,p.id as idp  FROM users u INNER JOIN profil p ON u.id_profil = p.id")->fetchAll();

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

<h2> <center>  <u> Gestion des utilisateurs </center>  </u> </h2>
<a href="#" class="add">Ajouter utilisateur</a>

<!--  form -->

<div class="adduser form">
      <h2>Ajouter utilisateur</h2>
      <span class="close"></span>
      <form id="form" method="post" action="/admin/gestion_des_utilisateurs">
      	 <p><input type="hidden" name="uid" value=""></p>
        <p><input type="text" name="nom" placeholder="Nom" value=""></p>
        <p><input type="text" name="prenom" placeholder="Prenom"></p>
        <p><input type="text" name="email" placeholder="E-mail"></p>
        <p><input type="text" name="username" placeholder="Username"></p>
        <p><input type="text" name="password" placeholder="Mot de passe"></p>
        <p><select name="profil"><option value="-">Choisi un profil</option><?php echo $option; ?></select></p>
        <p><input type="hidden" name="action" id="action" value=""></p>
        <p class="submit"><input type="submit" id="btnsubmit" name="submit" value="Ajouter"></p>
      </form>
    </div>

 <!-- Liste -->
<table>
	<thead>
		<tr>
		<td>Nom</td>
		<td>Prénom</td>
		<td>E-mail</td>
		<td>Username</td>
		<td>Profil</td>
		<td>Opérations</td>
		</tr>
	</thead>
<tbody>
<?php foreach ($data as $d): ?>
	<tr id="data-<?php echo $d->id; ?>" data-value="<?php echo $d->id ?>">
	<td id="nom" data-value="<?php echo $d->nom ?>"><?php echo $d->nom ?></td>
	<td id="prenom" data-value="<?php echo $d->prenom ?>"><?php echo $d->prenom ?></td>
	<td id="email" data-value="<?php echo $d->email ?>"><?php echo $d->email ?></td>
	<td id="username" data-value="<?php echo $d->username ?>"><?php echo $d->username ?></td>
	<td id="profil" data-value="<?php echo $d->idp ?>"><?php echo $d->libelle ?></td>
	<td>
		<a class="update" data-value="data-<?php echo $d->id ?>" href="#?act=upd&uid=<?php echo $d->id; ?>">Modifier</a>
		<?php if($d->id != 1 ): ?><a href="/admin/gestion_des_utilisateurs?act=del&uid=<?php echo $d->id; ?>" data-uid="<?php echo $d->id; ?>" class="delete user" onclick="return confirm('voulez vous vraiment supprimer')">Supprimer</a><?php endif; ?> 

	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


