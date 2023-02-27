<?php
function isSecure() {
	$isSecure = false;
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$isSecure = true;
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		$isSecure = true;
	}
	return $isSecure;
}


$httpsURL = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(!isSecure()){// && !isset($_POST["API"])
	if(count($_POST) > 0){
		echo 'Page should be accessed with HTTPS, but a POST Submission has been sent here. Adjust the form to point to '.$httpsURL;
	} else if(!headers_sent()){
		header("Status: 301 Moved Permanently");
		header("Location: $httpsURL");
		exit();
	} else {
		die('<script type="javascript">document.location.href="'.$httpsURL.'";</script>');
	}
}
?>
