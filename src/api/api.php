<?php

namespace MagratheaCloud;

use Animateka\Authentication\AuthApi;
use Magrathea2\Config;
use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use MagratheaCloud\Apikey\ApikeyApi;
use MagratheaCloud\Crawl\CrawlApi;

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
			"content-type",
		]);
		$this->SetUrl();
		$this->SetAuth();
		$this->OtherEndpoints();
		$this->AddApikey();
		$this->AddCrawl();
	}

	private function SetUrl() {
		$url = Config::Instance()->Get("app_url");
		$this->SetAddress($url);
	}

	private function OtherEndpoints() {
		$this->Add("POST", "clean", null, function($params) {
			$str = @$_POST["string"];
			if(!$str) throw new MagratheaApiException("empty string for cleaning (POST['string'])");
			return CloudHelper::cleanString($str);
		}, self::OPEN);
	}

	private function SetAuth() {
		$authApi = new AuthApi();
		$this->BaseAuthorization($authApi, self::LOGGED);
		$this->Add("GET", "token", $authApi, "GetTokenInfo", self::OPEN);
		$this->Add("POST", "login", $authApi, "Login", self::OPEN);
	}

	private function AddApikey() {
		$api = new ApikeyApi();
		$this->Add("POST", "keys", $api, "Create", self::LOGGED);
		$this->Add("GET", "keys", $api, "GetAll", self::LOGGED);
		$this->Add("GET", "key/:key/view", $api, "GetByKey", self::OPEN);
	}

	private function AddCrawl() {
		$api = new CrawlApi();
//		$this->Crud("crawl", $api, self::LOGGED);
		$this->Add("GET", "key/:key/crawls", $api, "GetByApi", self::LOGGED, "get crawls by key");
		$this->Add("POST", "key/:key/crawl", $api, "CreateByKey", self::LOGGED, "create a crawl");
		$this->Add("GET", "crawls/status", $api, "GetEnumCrawlStatus", self::OPEN);
		$this->Add("POST", "key/:key/crawl/:crawl/execute", $api, "ExecuteCrawl", self::LOGGED, "execute");
		$this->Add("GET", "key/:key/crawl/:crawl/report", $api, "ViewReport", self::LOGGED, "returns crawl report");
		$this->Add("GET", "test", $api, "Test",  self::OPEN);
	}

}
