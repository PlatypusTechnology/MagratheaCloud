<?php

namespace MagratheaCloud;

use Magrathea2\Config;
use Magrathea2\MagratheaApi;
use MagratheaCloud\Apikey\ApikeyApi;

class MagratheaCloudApi extends MagratheaApi {

	public $authApi = null;
	const OPEN = false;
	const LOGGED = "IsLogged";
	const ADMIN = "IsAdmin"; //

	public function __construct() {
		$this->Initialize();
	}
	public function Initialize() {
		\Magrathea2\MagratheaPHP::Instance()->StartDb();
		$this->AllowAll();
		$this->AddAcceptHeaders([
			"Authorization",
			"Access-Control-Allow-Origin",
			"cache-control",
			"x-requested-with",
		]);
		$this->SetAuth();
		$this->SetUrl();
		$this->AddApikey();
	}

	private function SetAuth() {
		$authApi = new \Magrathea2\MagratheaApiAuth();
		$this->BaseAuthorization($authApi, self::LOGGED);
		$this->Add("GET", "token", $authApi, "GetTokenInfo", self::OPEN);
//		$this->Add("POST", "login", $authApi, "Login", self::OPEN);
	}

	private function SetUrl() {
		$url = Config::Instance()->Get("app_url");
		$this->SetAddress($url);
	}

	private function AddApikey() {
		$api = new ApikeyApi();
		$this->Add("GET", "keys", $api, "GetAll", self::LOGGED);
		$this->Add("GET", "key/:key/view", $api, "GetByKey", self::OPEN);
		$this->Add("GET", "key/:key/images", $api, "ViewImages", self::OPEN);
	}

}
