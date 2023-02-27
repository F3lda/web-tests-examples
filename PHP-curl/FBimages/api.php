<!doctype html>
<html>
    <head>
		<title>Facebook curl images feed</title>
		<script type="text/javascript">
			var addEvent = function(object, type, callback) {
				if (object == null || typeof(object) == 'undefined') return;
				if (object.addEventListener) {
					object.addEventListener(type, callback, false);
				} else if (object.attachEvent) {
					object.attachEvent("on" + type, callback);
				} else {
					object["on"+type] = callback;
				}
			};
			
			addEvent(window, "load", function(event) {
				console.log('load');
				var imagesString = "";
				for(var i = 0; i < document.images.length; i++){
					if(imagesString != ""){
						imagesString += "<;;;>";
					}
					imagesString += document.images[i].src;
				}
				window.parent.postMessage(imagesString, "http://localhost") // location.origin); //document.referrer
			});
		</script>
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
	
	function curl_facebook_get_page($pageUrl)// URL without facebook.com/
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
		$url = 'https://www.facebook.com/'.$pageUrl;
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
	
	function curl_facebook_get_page_ID($pageUrlName)
	{
		$webPage = curl_facebook_get_page($pageUrlName."/photos");
		$webPage = get_string_between($webPage,'<div id="objects_container">','">See All ');
		$tempStr = get_string_between($webPage,'href="','"');
		return get_string_between($tempStr,$pageUrlName.'/photos/pb.','.');
	}
	
	function curl_facebook_get_photos_URL_by_page_ID($pageID)
	{
		$webPage = curl_facebook_get_page("media/set/?set=pb.".$pageID);
		$tempStr = get_string_between($webPage,'media/set/?set=pb.'.$pageID,'&');
		return "media/set/?set=pb.".$pageID.$tempStr."&s="; //s=<index of first image> - indexed from 0
	}
	
	function curl_facebook_get_12_photos_from_Xth($photoIndex,$photosUrl,$pageUrlName)
	{
		$imageArray = array();
		$webPage = curl_facebook_get_page($photosUrl . $photoIndex);
		do{
			$tempStr = get_string_between($webPage,$pageUrlName.'/photos/','" class');
			$tempStr = $pageUrlName.'/photos/'.$tempStr;
			$webPage = substr($webPage,strpos($webPage, $tempStr)+strlen($tempStr)+1);
			if($pageUrlName.'/photos/' != $tempStr){
				$tempStr = curl_facebook_get_page($tempStr);
				$tempArray = explode('<a href', $tempStr);
				$tempStr = end($tempArray);
				$tempStr = get_string_between($tempStr,'="','"');
				array_push($imageArray, $tempStr);
			} else {
				$webPage = false;
			}
		}while($webPage != false);
		return $imageArray;
	}
	
	function curl_facebook_get_X_photos($pageUrlName,$count)
	{
		$imageArray = array();
		while(count($imageArray) < $count){
			$imageArrayTemp = curl_facebook_get_12_photos_from_Xth(count($imageArray),curl_facebook_get_photos_URL_by_page_ID(curl_facebook_get_page_ID($pageUrlName)),$pageUrlName);
			$imageArray = array_merge($imageArray,$imageArrayTemp);
		}
		while(count($imageArray) > $count){
			array_pop($imageArray);
		}
		return $imageArray;
	}
	
	
	
	/*$imageArray = curl_facebook_get_X_photos("NHL",10);
	foreach ($imageArray as $imageUrl) {
		echo "<img src='".$imageUrl."'><br>";
	}*/
    echo curl_facebook_get_page("NHL"."/photos");
	?>
    </body>
</html>
