<?php

// get user name from query string
$query_string = $_SERVER['QUERY_STRING'];
$visitor_name = '';

foreach (explode('&', $query_string) as $query) {
	$info = explode('=', $query);
	if ($info[0] == 'visitor_name') {
		$visitor_name = urldecode($info[1]);
	}
}

// get user ip
$user_ip = $_SERVER['REMOTE_ADDR'];

// get user location from Ip
$ip_location_api_url = "http://ip-api.com/php/" . $user_ip;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $ip_location_api_url);
$result = curl_exec($ch);
curl_close($ch);
$result = unserialize($result);
$user_lon = $result['lat'];
$user_lat = $result['lon'];
$user_location = $result["city"];

// get weather for location
$weather_api_url = "https://api.open-meteo.com/v1/forecast?latitude=" . $user_lat . "&longitude=" . $user_lon . "&current=temperature_2m";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $weather_api_url);
$result = curl_exec($ch);
curl_close($ch);

$weather = json_decode($result);
$temperature = $weather->current->temperature_2m;

// prepare response
$response = [
	"client_ip" => $user_ip,
	"location" => $user_location,
	"greeting" => "Hello, " . $visitor_name . "!, the temperature is " . $temperature . " degrees Celcius in " . $user_location
];

// send response
header('Content-Type: application/json');
echo json_encode($response);
exit;