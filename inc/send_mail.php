<?php

function send_mail($to,$name,$subject,$body)
{

	require("PHPMailer_5.2.4/class.phpmailer.php");
	//require("/PHPMailer-5.2.20/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->From = "for.medtechapp@gmail.com";
	$mail->FromName = "Medtech App";
	$mail->Host = "smtp.gmail.com"; // specif smtp server
	$mail->SMTPSecure= "ssl"; // Used instead of TLS when only POP mail is selected
	$mail->Port = 465; // Used instead of 587 when only POP mail is selected
	$mail->SMTPAuth = true;
	$mail->Username = "for.medtechapp@gmail.com"; // SMTP username
	$mail->Password = "2H2O=H3O+OH"; // SMTP password
	$mail->AddAddress($to,$name); //replace myname and mypassword to yours
	$mail->AddReplyTo("for.medtechapp@gmail.com", "Medtech App");
	$mail->WordWrap = 50; // set word wrap
	//$mail->AddAttachment("c:\\temp\\js-bak.sql"); // add attachments
	//$mail->AddAttachment("c:/temp/11-10-00.zip");

	$mail->IsHTML(true); // set email format to HTML
	$mail->Subject = $subject;
	$mail->Body = $body;

	if($mail->Send()) {echo "Send mail successfully<br>";}
	else {echo "Send mail fail<br>";}

}

?>