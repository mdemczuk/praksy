<?php
	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$key = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO';

		function encryptthis($data, $key) {
		$encryption_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
		}

		function decryptthis($data, $key) {
		$encryption_key = base64_decode($key);
		list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
		}
	}
?>