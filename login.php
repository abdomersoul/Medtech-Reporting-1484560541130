<?php

if(!empty($_POST)){
	if($Auth->login($_POST)){
      header("Location:accueil");
	}
	else{
		echo "Erreur au niveau identifiants";
	}
}

if(!empty($_SESSION))
{
	header("Location:accueil");
}

?>

<div class="login">
      <h2>Connexion</h2>
      <form method="post" action="login">
        <p><input type="text" name="username" placeholder="Username"></p>
        <p><input type="password" name="password" placeholder="Password"></p>
        <p class="submit"><input type="submit" value="Login"></p>
      </form>
    </div>