<?php
header('Content-Type: text/html; charset=utf-8');
	class Utils{

		public static function random_string($length) {
		    $key = '';
		    $keys = array_merge(range(0, 9), range('a', 'z'),range('A','Z'));

		    for ($i = 0; $i < $length; $i++) {
		        $key .= $keys[array_rand($keys)];
		    }

		    return $key;
		}

		public static function escribeLog($cadena,$tipo) {
			$arch = fopen(realpath( '.' )."/log/log_".date('Y-m-d').".txt", "a+");
			fwrite($arch, "[ Fecha:".date('Y-m-d H:i:s')." | Nav: ".$_SERVER['HTTP_USER_AGENT']." | IP: ".$_SERVER['REMOTE_ADDR']." | FILE: ".$_SERVER['PHP_SELF']." | Tipo: ". $tipo ."] Msg: ".$cadena ."\r\n");
			fclose($arch);
		}

		public static function get_file_contentURL($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		
	}
?>