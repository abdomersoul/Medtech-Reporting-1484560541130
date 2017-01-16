<?php

	$con = new connexion();

	if(!empty($_POST)){
		extract($_POST);
		if (isset($action) && $action == 'modifier') 
		{
			$cont = $con->query("UPDATE users SET password='".md5($password)."' WHERE id='".$_SESSION["Auth"]->id."'");
		}
	}
	
	$data = $con->query("SELECT username, password FROM users WHERE id='".$_SESSION["Auth"]->id."' ")->fetch();
	

?>

<script type="text/javascript">

$(function () 
{
	var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");

	function validatePassword()
	{
	  if(password.value != confirm_password.value) 
	  {
		confirm_password.setCustomValidity("Passwords Don't Match");
	  } 
	  else 
	  {
		confirm_password.setCustomValidity('');
	  }
	}

	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;
});

</script>


<h2>Update Profile</h2>

<!--  form -->


 <!-- Liste -->
	<table>
		<form action="/compte" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="modifier"/>
			<tr>
				<td>Mot de passe</td>
				<td><input name="password" type="password" id="password" required></input></td>
			</tr>
			<tr>
				<td>Confirmer le Mot de passe</td>
				<td><input name="password" type="password" id="confirm_password"required></input></td>
			</tr>
			<tr>
				<td></td>
				<td> <input type="submit" value="Valider" name="submit"> </td>
			</tr>
		</form>
</table>