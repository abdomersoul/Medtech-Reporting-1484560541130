<?php 
/**
* 
*/
class Auth
{
	
	function login($d)
	{	

		$con = new connexion();
		$data = $con->query("SELECT u.username,u.id_profil,p.libelle,u.id,u.password,u.nom,u.prenom FROM users u InNER JOIN profil p ON u.id_profil = p.id WHERE username=? AND password=?",[$d["username"],md5($d["password"])])->fetchAll();
		
		if(count($data) > 0) {
			$_SESSION["Auth"] = $data[0];

			$data = $con->query("SELECT action FROM permission WHERE id_profil=?",[$_SESSION["Auth"]->id_profil])->fetchAll();
			$_SESSION["Auth"]->actions[] = $data;
			return true;

		}
		else return false;
	}


	/*function allow($rang)
	{
		global $PDO;
		$req = $PDO->prepare("SELECT * FROM profil");
		$req->execute();
		$data = $req->fetchAll();
		echo var_dump($data);
		$profil = array();
		foreach ($data as $d) {
			$profil[$d->libelle] = $d->id;
		}

		if (!$this->user("libelle")) {
			$this->forbidden();
		}
		else{
			if ($profil[$rang] < $this->user("id_profil")) {
				$this->forbidden();
			}
		}
	}*/

	function allow($action){

		if (!isset($_SESSION["Auth"]) || empty($_SESSION["Auth"])) header("Location:login");

		foreach($_SESSION["Auth"]->actions[0] as $key => $val)
		  if($action == $val->action) return true;

		$this->forbidden();
	}

	function user($field){
		if (isset($_SESSION["Auth"]->$field)) {
			return $_SESSION["Auth"]->$field;
		}else return false;
	}

	function forbidden(){
		header("Location: /forbidden");
	}


}

$Auth = new Auth();
?>