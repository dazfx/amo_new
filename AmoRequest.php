<?php

class AmoRequest{
	//public $massiv_cltn=array();
	//public $massiv_time=array();

	public $fileSizeLimit = 5000000;

        protected $login;
        protected $domen;
        protected $api;




        public function __construct($login, $domen, $api){
            $this -> login = $login;
            $this -> domen = $domen;
            $this -> api = $api;
        }

	public function get_request($link) {

            $curl = curl_init();
            $linkFinish = 'https://' . $this->domen . '.amocrm.ru/api/v2/' . $link . 'USER_LOGIN=' . $this->login . '&USER_HASH=' . $this->api;
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
            curl_setopt($curl, CURLOPT_URL, $linkFinish);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
            curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $out = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
            $Response = json_decode($out, true);
            if ($code != 200 && $code != 204) {
                $resp[0] = $this->echo_error($code);
                $resp[1] = $Response;
                return $resp;
            } else {return $Response;}
        }

        public function get_request_array($link, $array) {
            $headers[] = "Accept: application/json";

            $linkFinish = 'https://' . $this->domen . '.amocrm.ru/api/v2/' . $link . 'USER_LOGIN=' . $this->login . '&USER_HASH=' . $this->api;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
            curl_setopt($curl, CURLOPT_URL, $linkFinish);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($array));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
            curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $out = curl_exec($curl);

            curl_close($curl);
            $Response = json_decode($out, true);
            return $Response;
        }


        public function prn($array){
            echo "<pre>";
                print_r($array);
            echo "</pre>";
        }


        public function prn_in_file(array $array, $name_file = '', $mod = 'w+'){

            $name_file = $name_file == '' ? $this->domen.'.txt' : $name_file;

            $str = "\r\n".date("Y-m-d H:i:s", time())."\r\n";
            $printArray = print_r($array, true);

            $desc = fopen($name_file, $mod);
                fwrite($desc, $str);
                fwrite($desc, $printArray);
            fclose($desc);

            if (filesize($name_file) >= $this->fileSizeLimit) {
		file_put_contents($name_file, substr(file_get_contents($name_file), -4000000, null, 'latin1'));
            }

        }

        public function rasp_mass_file($mass, $name_file = '', $mod = 'w+'){

            $name_file = $name_file == '' ? $this->domen.'.txt' : $name_file;

            $str = "\r\n".date("Y-m-d H:i:s", time())."\r\n";
            foreach ($mass as $key => $value){
		$str.= "$key -- ";
		if (gettype($value) == 'array'){

			foreach ($value as $key1=>$value1){
			$str.= "\r\n. . . . $key1 -- ";
			if (gettype($value1) == 'array')
				{
				foreach ($value1 as $key2=>$value2){
					$str.= "\r\n. . . . . . . . $key2 -- ";
					if (gettype($value2) == 'array')
						{
						foreach ($value2 as $key3=>$value3){
						$str.= "\r\n. . . . . . . . . . . . $key3 -- ";
						if (gettype($value3)=='array')
							{
							foreach ($value3 as $key4=>$value4){
							$str.= "\r\n. . . . . . . . . . . . . . . . $key4 -- ";
							if (gettype($value4) == 'array')
								{
								foreach ($value4 as $key5=>$value5){
								$str.= "\r\n. . . . . . . . . . . . . . . . . . . . $key5 -- ";
								if (gettype($value5) == 'array')
									{
									foreach ($value5 as $key6=>$value6){
									$str.= "\r\n. . . . . . . . . . . . . . . . . . . . . . . . $key6 -- ";
									if (gettype($value6)=='array')
										{
										foreach ($value6 as $key7=>$value7){
										$str.= "\r\n. . . . . . . . . . . . . . . . . . . . . . . . $key7 --  ";
										if(gettype($value7)=='array')
											{
											foreach ($value7 as $key8=>$value8){
											$str.= "\r\n. . . . . . . . . . . . . . . . . . . . . . . . . . . . . $key8 -- $value8 ";
											}}
										else{$str.= "$value7 \r\n";}
										}
									}
									else{$str.= "$value6\r\n";}
									}
									}
								else{$str.= "$value5\r\n";}
								}
								}
							else{$str.= " $value4\r\n";}
							}
							}
						else{$str.= " $value3\r\n";}
						}
						}
					else{$str.= " $value2\r\n";}
					}
				}
			else{$str.= " $value1\r\n";}
			}
		}
		else{$str.= "$value \r\n";}
		}
		$new_mass = preg_replace("/\s*\r+/", "", $str);
		file_put_contents($name_file, $new_mass, FILE_APPEND);

		if (filesize($name_file) >= $this -> fileSizeLimit) {
			file_put_contents($name_file, substr(file_get_contents($name_file), -4000000, null, 'latin1'));
		}
        }


        public function echo_error($code) {
            $massiv_code = array(101 => '101	Аккаунт не найден',
                    102 => '102	POST-параметры должны передаваться в формате JSON',
                    103 => '103	Параметры не переданы',
                    104 => '104	Запрашиваемый метод API не найден',
                    110 => '110	Неправильный логин или пароль	Общая ошибка авторизации.',
                    111 => '111	Неправильный код капчи',
                    112 => '112	Пользователь не состоит в данном аккаунте',
                    113 => '113	Доступ к данному аккаунту запрещён с Вашего IP адреса',
                    401 => '401	На сервере нет данных аккаунта. Нужно сделать запрос на другой сервер по переданному IP.',
                    404 => '404	Документ не найден. Видимо неверная ссылка',
                    200 => '200	Почему-то 200',
                    201 => '201	Добавление контактов: пустой массив',
                    202 => '202	Добавление контактов: нет прав',
                    203 => '203	Добавление контактов: системная ошибка при работе с дополнительными полями',
                    204 => '204	Добавление контактов: дополнительное поле не найдено',
                    205 => '205	Добавление контактов: контакт не создан',
                    206 => '206	Добавление/Обновление контактов: пустой запрос',
                    207 => '207	Добавление/Обновление контактов: неверный запрашиваемый метод',
                    208 => '208	Обновление контактов: пустой массив',
                    209 => '209	Обновление контактов: требуются параметры "id" и "last_modified"',
                    210 => '210	Обновление контактов: системная ошибка при работе с дополнительными полями',
                    211 => '211	Обновление контактов: дополнительное поле не найдено',
                    212 => '212	Обновление контактов: контакт не обновлён',
                    219 => '219	Список контактов: ошибка поиска, повторите запрос позднее',
                    213 => '213	Добавление сделок: пустой массив',
                    214 => '214	Добавление/Обновление сделок: пустой запрос',
                    215 => '215	Добавление/Обновление сделок: неверный запрашиваемый метод',
                    216 => '216	Обновление сделок: пустой массив',
                    217 => '217	Обновление сделок: требуются параметры "id", "last_modified", "status_id", "name"',
                    240 => '240	Добавление/Обновление сделок: неверный параметр "id" дополнительного поля',
                    218 => '218	Добавление событий: пустой массив',
                    221 => '221	Список событий: требуется тип',
                    222 => '222	Добавление/Обновление событий: пустой запрос',
                    223 => '223	Добавление/Обновление событий: неверный запрашиваемый метод (GET вместо POST)',
                    224 => '224	Обновление событий: пустой массив',
                    225 => '225	Обновление событий: события не найдены',
                    227 => '227	Добавление задач: пустой массив',
                    228 => '228	Добавление/Обновление задач: пустой запрос',
                    229 => '229	Добавление/Обновление задач: неверный запрашиваемый метод',
                    230 => '230	Обновление задач: пустой массив',
                    231 => '231	Обновление задач: задачи не найдены',
                    232 => '232	Добавление событий: ID элемента или тип элемента пустые либо неккоректные',
                    233 => '233	Добавление событий: по данному ID элемента не найдены некоторые контакты',
                    234 => '234	Добавление событий: по данному ID элемента не найдены некоторые сделки',
                    235 => '235	Добавление задач: не указан тип элемента',
                    236 => '236	Добавление задач: по данному ID элемента не найдены некоторые контакты',
                    237 => '237	Добавление задач: по данному ID элемента не найдены некоторые сделки',
                    238 => '238	Добавление контактов: отсутствует значение для дополнительного поля',
                    244 => '244	Добавление сделок: нет прав.',
                    400 => '400	Неверная структура массива передаваемых данных, либо не верные идентификаторы кастомных полей',
                    403 => '403	Аккаунт заблокирован, за неоднократное превышение количества запросов в секунду',
                    429 => '429	Превышено допустимое количество запросов в секунду',
                    2002 => '2002	По вашему запросу ничего не найдено');


            $arrRespError[] = $massiv_code[$code];
            return $arrRespError;
        }

}
?>