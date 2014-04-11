<?php
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$subject = "Message";
$body = "From $name, $email,  \n\n$message";

$to = "proofpuppy@proteadigital.com";

mail($to, $subject, $body);
?>