<?php

	if(empty($_POST['phone']) and empty($_POST['email'])) {
		die('11');
	}

	$phone = trim($_POST['phone']);
	$email = trim($_POST['email']);

	if(!empty($email) and !is_email_valid($email)) {
		die('10');
	}

	$sql = @new mysqli('127.0.0.1', 'ublurry', 'uU39248204', 'blurry', 3306);
		
	if ($sql->connect_errno) {
		die('2');
	}

	if(!$stmt = $sql->prepare('INSERT INTO registered (ip, phone, email) VALUES (?,?,?)')) {
		die('3');
	}
	
	$stmt->bind_param('sss', $_SERVER["REMOTE_ADDR"], $phone, $email);
	$stmt->execute();

	die('200');


	function is_email_valid($email) {
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}
?>