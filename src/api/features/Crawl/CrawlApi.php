<?php
namespace MagratheaCloud\Crawl;

use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use MagratheaCloud\Apikey\ApikeyControl;

class CrawlApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Crawl());
		$this->service = new CrawlControl();
	}
	
	private function _GetKey($params) {
		$key = $params["key"];
		if(empty($key)) {
			throw new MagratheaApiException("key [".$key."] is empty", true, 404, $params);
		}
		return $key;
	}
	private function _GetCrawl($params) {
		$key = $this->_GetKey($params);
		$crawlId = $params["crawl"];
		$crawl = new Crawl($crawlId);
		if(empty($crawlId) || empty($crawl->created_at)) {
			throw new MagratheaApiException("crawl [".$crawlId."] is invalid", true, 404, $params);
		}
		if(!$crawl->ValidateKey($key)) {
			throw new MagratheaApiException("key [".$key."] is not linked with crawl [".$crawlId."]");
		}
		return $crawl;
	}

	public function Test($params) {
		return "";
		// return $this->ExecuteCrawl([
		// 	"key" => "8wdDwTyxs3srfiYwRvL8",
		// 	"crawl" => 1,
		// ]);
	}

	public function CreateByKey($params) {
		$key = $this->_GetKey($params);
		return $this->service->Create($key);
	}

	public function GetByApi($params) {
		$key = $this->_GetKey($params);
		return $this->service->GetCrawlsByApi($key);
	}

	public function GetEnumCrawlStatus($params) {
		return EnumCrawlStatus::array();
	}

	public function ExecuteCrawl($params) {
		$crawl = $this->_GetCrawl($params);
		if($crawl->IsWaiting()) {
			$runner = new CrawlExecuter($crawl);
			$rs = $runner->Run();
			return $rs;
		}
		return [
			"error" => "Crawl execution already started",
			"id" => $crawl->id,
			"status" => $crawl->status,			
			"executed_at" => $crawl->executed_at,
			"report" => $crawl->result,
		];
	}

	public function ViewReport($params) {
		$crawl = $this->_GetCrawl($params);
		return [
			"id" => $crawl->id,
			"key" => $params["key"],
			"executed_at" => $crawl->executed_at,
			"report" => json_decode($crawl->result),
		];
	}

}
