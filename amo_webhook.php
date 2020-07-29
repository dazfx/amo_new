<?php
header( 'Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);



require 'fnct.php';
require 'config.php';
require 'AmoRequest.php';





$obj = new AmoRequest($login, $domain, $api);
//МЕНЯЕМ СПОСОБ АВТОРИЗАЦИИ НА НОВЫЙ




$company_name = $_GET['c'];
$revard = $_REQUEST['m'];


//if ($company_name == "") {die();}


$body = $_REQUEST['leads'];

//$lead_id = $body['status'][0]['id'];
//$status_id = $body['status'][0]['status_id'];


$viber_id = $body['status'][0]['custom_fields'][0]['values'][0]['value'];
//как-то бы получать значение по имени кастомного поля или ID (например viber_id или 385229)



/*
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
МЕТИМ КОНТАКТ И ЛИД ТЕГОМ $company_name
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/



send_message($viber_id, $company_name' | '.$revard, $ss_api);



















/*
function get_status($status1)
{
	$status = array (
		'33248242' => 'new',
		'142' => 'payed',
		'143' => 'false',
		'33234493' => 'in_work',
		'33234496' => 'payment_waiting',
		'33234499' => 'part_payed'
	);
	return $status[$status1];
}

$statusName = get_status($status_id);


function getLeadData($idLead, $obj)
{
	return $obj -> get_request('leads?id=' . $idLead);
}


$testData = getLeadData($lead_id, $obj);

//$obj -> prn($testData);

$client_id = $testData["_embedded"]["items"][0]["main_contact"]["id"];

//echo $client_id;


function getClientData($client_id, $obj)
{
	return $obj -> get_request('contacts?id=' . $client_id);
}

$client_info = getClientData($client_id, $obj);

//$obj -> prn($client_info);

$phone = $client_info["_embedded"]["items"][0]['custom_fields'][0]['values'][0]['value'];
$email = $client_info["_embedded"]["items"][0]['custom_fields'][1]['values'][0]['value'];
*/
?>



