<?php
namespace MagratheaCloud;

use Magrathea2\Exceptions\MagratheaApiException;
use MagratheaCloud\Apikey\ApikeyControl;

class ApiHelper {
	public static function GetKey($params) {
		$key = $params["key"];
		if(empty($key)) {
			throw new MagratheaApiException("key [".$key."] is empty", true, 404, $params);
		}
		return $key;
	}

	public static function GetApiKey($params) {
		$key = self::GetKey($params);
		$keyService = new ApikeyControl();
		$apiKey = $keyService->GetByKey($key);
		if(!$apiKey) {
			throw new MagratheaApiException("key [".$key."] does not exists", true, 500);
		}
		return $apiKey;
	}

}

