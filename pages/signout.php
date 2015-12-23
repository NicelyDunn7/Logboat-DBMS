<?php
	//If the user heads to an unsecured page, redirect to a secured page
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
           $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
           header('Location: ' . $url);
            //exit;
        }
	//End the user's session so their information is protected
        session_start();
	session_unset();
	session_destroy();
	header('Location: https://logboat1.cloudapp.net/index.php');
?>
