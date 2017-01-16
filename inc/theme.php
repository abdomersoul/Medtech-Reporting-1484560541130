<!DOCTYPE html>

<?php 

if(isset($_SESSION["Auth"]->id))
{
	$conn = new connexion();
	$data = $conn->query(" SELECT COUNT(*) as num FROM notification WHERE id_user_notification_for='".$_SESSION["Auth"]->id."' AND is_new_notification=1 ")->fetch(); 
	$notification_num = $data->num;
}
?>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Medtech</title>
  <link rel="stylesheet" href="/inc/css/style.css">
  <script type="text/javascript" src="/inc/js/jquery-1.12.0.min.js"></script>
  <script type="text/javascript" src="/inc/js/script.js"></script>
  <link rel="stylesheet" href="/inc/font-awesome-4.7.0/css/font-awesome.min.css">
  
</head>
<body>
	<section class="header">
		<a href="/accueil" > <h1 id="logo">MedTech</h1> </a>
		<?php if (isset($_SESSION["Auth"])): ?>
		<div class="smenu">
			<ul>
				<li style="color: #08c;font-size: 12px;line-height: 50px;padding: 0 5px;text-decoration: none;color:#08c"><?php echo "Bonjours, ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom."" ; ?></li>
				<li><a href='/compte'>Mon compte</a></li>
				<li><a href='/notifications'>
					<i class="fa fa-bell-o" <?php if($notification_num!=0) echo "style='color:red;'"; ?> ></i>
					<span <?php if($notification_num!=0) echo "style='color:red;'"; ?>>
						<?php echo $notification_num ;?>
					</span></a></li>
				<li><a href="/logout">Se déconnecter</a></li>
			</ul>
		</div>
		<?php endif; ?>
	</section>

	<?php if (isset($_SESSION["Auth"])) include "inc/menu.php"; ?>
	<section class="container <?php if (isset($_SESSION['Auth'])) echo 'cols'; ?>">
	    <?php echo $content; ?>
	</section>
</body>
</html>