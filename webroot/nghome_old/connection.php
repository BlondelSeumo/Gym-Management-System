<?php
/* $servername = "localhost";
$username = "root";
$password = "";
$dbname="gym_mst";
$server_path = 'http://192.168.1.22/php/cakephp/gym_master/';
$image_path='http://192.168.1.22/php/cakephp/gym_master/webroot/upload/';
$image_pa=$image_path; */
$servername = "localhost";
$username = "root";
$password = "";
$dbname="gym_master";
$server_path = 'http://192.168.1.100/Nilkanth/19_06_2020/gym_master/gym_master/';
$image_path='http://192.168.1.100/Nilkanth/19_06_2020/gym_master/gym_master/webroot/upload/';
$image_pa=$image_path;
$conn = new mysqli($servername,$username, $password,$dbname);

if (!function_exists('custom_http_response_code'))
{
	function custom_http_response_code($code = NULL)
	{
		if ($code !== NULL)
		{
			switch ($code)
			{
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'Success / All ok'; break;
				case 201: $text = 'Success / All ok'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'invalid input, object invalid'; break;
				case 401: $text = 'Authentication credentials were missing or incorrect'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'The URI requested is invalid or the resource requested, such as a user, does not exist. Also returned when the requested format is not supported by the requested method.'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'an existing item already exists'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Something went wrong on server'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Server is overloaded with requests. Try again later'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}

			// $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			// header($protocol . ' ' . $code . ' ' . $text);
			// $GLOBALS['http_response_code'] = $code;

			// $code = $code.': '.$text;
			$code = $text;

		}
		else
		{
			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
		}
		return $code;
	}
}

if (!function_exists('SendNotification'))
{
	function SendNotification($token,$title,$text,$bicon,$data_key,$data_value)
	{
		// $token = "duj1xtMwkyE:APA91bEvtQxlPWsDKHFN8XlCg8wUozKaSFuogEJXeNLbHz3TE4175gOthX68JImTsJQWwFz4XKbzY-PAaXUvjVOIEc07VuQrJ-_pAiPAzD2SIVKlNkPTM92uls5fsCIF7eJSdrYqE0S2";
		$send_notification = json_encode(array('registration_ids'=>array($token),'notification'=>array('title'=>$title,'text'=>$text,'badge'=>$bicon,'sound'=>1),'data'=>array($data_key=>$data_value)));

		$curl = curl_init();
			 curl_setopt_array($curl, array(
			 CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
			 CURLOPT_RETURNTRANSFER => true,
			 CURLOPT_ENCODING => "",
			 CURLOPT_MAXREDIRS => 10,
			 CURLOPT_TIMEOUT => 300,
			 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			 CURLOPT_CUSTOMREQUEST => "POST",
			 CURLOPT_POSTFIELDS => $send_notification,
			 CURLOPT_SSL_VERIFYHOST=> 0,
			 CURLOPT_SSL_VERIFYPEER=> 0,
			 CURLOPT_HTTPHEADER => array(
				"authorization: key=AIzaSyBx5HbuFdDtIvhMGMl7rUk2V-sF-JmlRjM",
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: ff7ad440-bbe0-6a2a-160d-83369683bc63"
			 ),
			));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		// var_dump($response);exit;
		return $response;
	}
}
?>
