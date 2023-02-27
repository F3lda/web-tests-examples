<!doctype html>
<html>
    <head>
		<title>Facebook curl images feed</title>
	</head>
	<body>
	<?php
	function get_string_between($string, $start, $end)
	{
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	function curl_facebook_get_page()
	{
		$request = array();
		$request[] = 'Host: m.facebook.com';
		$request[] = 'User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:39.0) Gecko/20100101 Firefox/39.0';
		$request[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$request[] = 'Accept-Language: en-US,en;q=0.5';
		$request[] = 'Accept-Encoding: gzip, deflate';
		$request[] = 'DNT: 1';
		$request[] = 'Cookie: datr=x; fr=x; lu=x s=xx; csm=x; xs=xx; c_user=x; p=-2; act=x; presence=x; noscript=1';
		$request[] = 'Connection: keep-alive';
		$request[] = 'Pragma: no-cache';
		$request[] = 'Cache-Control: no-cache';
		$url = 'https://www.facebook.com/NHL';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_ENCODING,"");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FILETIME, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,100);
		curl_setopt($ch, CURLOPT_FAILONERROR,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request);

		$data = curl_exec($ch);
		if (curl_errno($ch)){
			$data .= 'Retreive Base Page Error: ' . curl_error($ch);
		} else {
			$skip = intval(curl_getinfo($ch, CURLINFO_HEADER_SIZE)); 
			$responseHeader = substr($data, 0, $skip);
			$data = substr($data, $skip);
			//echo $data;
			//echo $responseHeader;
		}
		return $data;
	}
	
	echo curl_facebook_get_page();
	?>
    </body>
</html>
