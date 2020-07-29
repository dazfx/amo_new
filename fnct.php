<?php

include ('mc_config.php');


$body_json = file_get_contents('php://input');
$body = json_decode($body_json, true);


function get_user_info($user_id)
{
	$query = $GLOBALS['mysqli']->query("SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1");


	while($row = mysqli_fetch_assoc($query))
	{
		$currency = $row['currency'];
		$cart1 = $row['cart'];
		$status = $row['status'];
		$del = $row['rate'];
		$tips = $row['tips'];
		$email = $row['email'];
		if ($tips == "") {$tips = 0;}
		$discount = $row['discount'];
		if ($discount == "") {$discount = 0;}
	}



	
	$row = mysqli_fetch_array($query);
	$arr = json_decode($row[0], true);
	return $arr;
}



function update_user_info($user_id, $arr)
{
	mysqli_query($GLOBALS['mysqli'], "UPDATE users SET ballance = '$ballance' WHERE user_id = '$user_id'");
}


function create_user($user_id, $arr)
{
	$result = $GLOBALS['mysqli']->query("SELECT * FROM users WHERE user_id = '$user_id'");


	$sql_r = "INSERT INTO orders VALUES ('','".$user_id."','".$ballance."','".$first_name."','".$last_name."','".$email."','".$phone."','".$addr."','".$last."','".$created."')";

	if($result->num_rows != 0) {
		$con = mysqli_query ($GLOBALS['mysqli'], $sql_r);
	}
}


function send_message($user_id, $message, $api_key)
{
	$message = '{"content":"'.$message.'", "type":"text", "watermark":"'.time().'"}';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'https://api.smartsender.eu/v1/contacts/'.$user_id.'/send');
	curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		"Authorization: " . $api_key,
		'accept: application/json'
	));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
}





function send_message_funnel($user_id, $funnel_id, $api_key)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.smartsender.eu/v1/contacts/' . $user_id . '/funnels/' . $funnel_id);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		"Authorization: " . $api_key,
		'accept: application/json'
	));
  //Bearer + ключ апи из админки SmartSender

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
}




function getCustomField($user_id, $name, $api_key)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.smartsender.eu/v1/contacts/' . $user_id);
  //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		"Authorization: " . $api_key,
		'accept: application/json'
	));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output1 = curl_exec($ch);
	curl_close ($ch);

	$arr = json_decode($server_output1, true);

	array_walk($arr['variables'], function($v, $k) use (&$arr, &$name, &$xxx){
		if ($v['name'] == $name) {$xxx = $v['value'];}
	}); 

	return $xxx;
}


//echo getCustomField("314065", "dz1", $api_key1);







function setCustomField($user_id, $name, $value, $api_key)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.smartsender.eu/v1/contacts/' . $user_id);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		"Authorization: " . $api_key,
		'accept: application/json'
	));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'values' => [
			$name => $value,
		]

	]));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
}





//setCustomField("314065", "dz1", "1111", $api_key1);
?>							