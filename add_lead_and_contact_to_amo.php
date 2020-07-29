<?php
//нужно изменить способ авторизации с API на oAuth (с небольшой инструкцией, как это все работает):

//Секрктный ключ: tOzke79p8FRhheQSICUsZ44XWlWWA5rhEd8tHfaeiHxZx1m5JLgTEV63T79DsuFm
//ID интеграциии: 9569dbd8-9173-416a-b7d2-78efba2d8ee4
//Код авторизации (20минут): def50200c9e4f82650cd6ce1ceb573e550831f39bfcb23e8b9bac550ad4aa319170ff47e12d906e1a3f7753bf1e9489e8da6ca6131406e8a00156646d43cc611d987c6307de7e512eacc1e04d3d419ab8db85cd1a57a059b1b0939216fe91b1b03e7500429591946a0e379b677e4147add9a3225f8cd20c7939396d32de70b48ddc6bbae53ca6c77e06c7348ff22c19519cb6adc665e34861ca8b9b671eb4a8cc1e671d3351bcbc1fd61ff5f683a4f7b6c93c3e5abf0f10b942278a80ddc8fac7fd7e014b89bf92f24d6620e45e657413cc3a9e29f0fa2a452bef84283113dc344e51fb59243eb07ac70e841b5961b50711f5f15802f169d46036fee8bcfb34c64805301ab0959fa0b93cddf36d1d6f862f85c13cc0f80e1ce77862e3f72bf9379a6d043ee4768efc83c636197e27220a9e398c64d5d6d6c139fba6508228421d4e5a5e582b3d2b4685ca2b3b267c45ffdd3a9577403267a2ec4eaeba315c4758cae7bc3d3599c0892504d327ee6d3321b9648b5e0dc0a5a2c581640cda1f9456179384725a659d8a12caf56e6efef36d4d08f1a9da5e003076892a6085f854a7bce0f4ad2f8def7f8fdbfd653ee02e066a9a4f9d6f268e3efdf7d26

//Если нужно, дам админский доступ к АМО


//нужно добавить возможность прикреплять к лиду картинку в виде примечания (коммента) из url ($url = $_GET['url']). Те на вход будет поступать url изображения и нужно его прикрепить в виде коммента к лиду.


//при создании контакта проверять его на уникальность по кастомному полю viber_id (для примера берем любой id кастомного поля). А также плюс по телефону и емейлу. Если есть хоть одно совпадение у какого-то из контактов - не создавать новый. По-моему это реализовано, но без кастомного поля. Кастомное поле в приоритете. Это id пользователя в чат-боте (передаем его на первом этапе) и оно уникально

//написать функции setLeadTag($lead_id,$tags_list) и setContactTag($contact_id,$tags_list) для добавления тегов к лиду и к контакту. и такие же RemoveTag.


header( 'Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require 'fnct.php';
require 'config.php';
require 'AmoRequest.php';





$obj = new AmoRequest($login, $domain, $api);
//!!!!!!!!!!!!!!! МЕНЯЕМ СПОСОБ АВТОРИЗАЦИИ НА НОВЫЙ




$url = $_GET['url'];
$email = $_GET['email'];
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$phone = $_GET['phone'];





//******Вывод всех воронок и статусов
function getStatus($pipeline = 0, $statusName = 0, $obj)
{
  $dataAccountStat = $obj -> get_request('account?with=pipelines&');
    //$obj -> prn($dataAccountStat);
  foreach($dataAccountStat['_embedded']['pipelines'] as $keyStat => $valStat){
    foreach($valStat['statuses'] as $keyS => $valS){
      if(trim(mb_strtolower($valS['name'])) == trim(mb_strtolower($statusName))){
        $arrStatus[] = $keyS;
        $arrStatus[] = $keyStat;
        return $arrStatus;
      }
    }
  }
}

$arrStatus = getStatus($pipeline, $statusName, $obj);
//$obj -> prn($arrStatus);



function addLead($pipeline = 0,
 $statusId = 0,
 $responsible_user_id = 0,
 $email = '',
 $phone = 0,
 $fieldContactPhone,
 $fieldContactEmail,
 $obj, $viber_id, $first_name, $last_name)
{

  $arrAddLead["add"] = array(array("name" => "ИМЯ СДЕЛКИ",
    "created_at" => time(),
    "status_id" => $statusId,
    "pipeline_id" => $pipeline[1],
    "tags" => '',
    //"sale" => $price,
    'custom_fields' => array(array('id' => '385229', 'values' => array(array('value' => $viber_id)))
  )));



  if((int)$responsible_user_id != 0)
  {
    $arrAddLead["add"][0]['responsible_user_id'] = $responsible_user_id;
  }

  $mod_1 = $obj -> get_request_array('leads?', $arrAddLead);




  //проверяем есть ли уже такой контакт как я описывал выше
  //!!!!!!!!!!!!!!! также проверяем на уникальность по viber_id
  $addContacts['add'] = array(array(
    'first_name' => $first_name,
    'last_name' => $last_name,
    "leads_id" => $mod_1["_embedded"]["items"][0]["id"],
    'custom_fields' => array(array('id' => $fieldContactPhone,
     'values' => array(array('value' => ($phone) ? $phone : '',
       'enum' => 'WORK'))),
    array('id' => $fieldContactEmail,
     'values' => array(array('value' => ($email) ? $email : '',
       'enum' => 'WORK'))))));
  //!!!!!!!!!!!!!!! добавляем кастомное поле viber_id к контакту




  if((int)$responsible_user_id != 0)
  {
    $addContacts["add"][0]['responsible_user_id'] = $responsible_user_id;
  }
  $mod_3 = $obj->get_request_array('contacts?', $addContacts);

  return $mod_1["_embedded"]["items"][0]["id"];

}



$dataNewLead = addLead($arrStatus[1], $arrStatus[0], 0, $email, $phone, $fieldContactPhone, $fieldContactEmail, $obj, $viber_id, $first_name, $last_name);
//!!!!!!!!!!!!!!! не совсем понятно за что отвечают первые три элемента функции




//echo $dataNewLead;


?>