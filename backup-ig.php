<?php
set_time_limit(0);
ignore_user_abort(1);
	function proccess($ighost, $useragent, $url, $cookie = 0, $data = 0, $httpheader = array(), $proxy = 0, $userpwd = 0, $is_socks5 = 0){
		$url = $ighost ? 'https://i.instagram.com/api/v1/' . $url : $url;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		if($proxy) curl_setopt($ch, CURLOPT_PROXY, $proxy);
		if($userpwd) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpwd);
		if($is_socks5) curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		if($httpheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		if ($data):
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		endif;
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch);
		if(!$httpcode) return false; else{
			$header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			curl_close($ch);
			return array($header, $body);
		}
	}
	function generate_useragent($sign_version = '6.22.0'){
		$resolusi = array('1080x1776','1080x1920','720x1280', '320x480', '480x800', '1024x768', '1280x720', '768x1024', '480x320');
		$versi = array('GT-N7000', 'SM-N9000', 'GT-I9220', 'GT-I9100');		$dpi = array('120', '160', '320', '240');
		$ver = $versi[array_rand($versi)];
		return 'Instagram '.$sign_version.' Android ('.mt_rand(10,11).'/'.mt_rand(1,3).'.'.mt_rand(3,5).'.'.mt_rand(0,5).'; '.$dpi[array_rand($dpi)].'; '.$resolusi[array_rand($resolusi)].'; samsung; '.$ver.'; '.$ver.'; smdkc210; en_US)';
	}
	
	function hook($data) {
		return 'ig_sig_key_version=4&signed_body=' . hash_hmac('sha256', $data, '469862b7e45f078550a0db3687f51ef03005573121a3a7e8d7f43eddb3584a36') . '.' . urlencode($data); 
	}
	function generate_device_id(){
		return 'android-' . md5(rand(1000, 9999)).rand(2, 9);
	}
	function generate_guid($tipe = 0){
		$guid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand(0, 65535), 
		mt_rand(0, 65535),
		mt_rand(0, 65535),
		mt_rand(16384, 20479), 
		mt_rand(32768, 49151),
		mt_rand(0, 65535), 
		mt_rand(0, 65535), 
		mt_rand(0, 65535));
		return $tipe ? $guid : str_replace('-', '', $guid);
	}
?>
