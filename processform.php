<?php

	header('Location: http://www.vaishnavk.com/thankyou.html') ;

        $from_add = "me@vaishnavk.com"; 
	$to_add = "vaikam@umich.edu"; 
	$subject = "Contact Mail";
	$message = "You have a message from \r\n";
	$message .= $_POST['name'];
    $message .= "\r\n The message is \r\n";
	$message .= $_POST['message'];
	$message .= "\r\n";
	$message .= "The persons email address is \r\n";
	$message .= $_POST['email']; 
	$headers = "From: Portfolio Website <contact@vaishnavk.com>\r\n";
	$headers .= "Reply-To: vaikam@umich.edu \r\n";
	$headers .= "Return-Path: Hello\r\n";
	$headers .= "X-Mailer: PHP \r\n";
	if(mail($to_add,$subject,$message,$headers)) 
	{
		$msg = "Mail sent OK";  
    }
?>