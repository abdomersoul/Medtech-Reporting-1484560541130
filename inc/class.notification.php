<?php
	
	
	class notification
	{
		function send_notification($from,$to,$text,$date)
		{
			$con = new connexion();
			$res = $con->query("INSERT INTO notification(id_user,id_user_notification_for,action,date_action) VALUES (".$from.",".$to.",'".$text."','".$date."')");
		}
		
		function get_notification($id)
		{
			$con = new connexion();
			$res = $con->query("SELECT * FROM notification WHERE id_user_notification_for = ".($id))->fetchAll();
			return $res;
		}
	}
?>