<?php
namespace MagratheaCloud\Crawl;

use Magrathea2\DB\Query;
use Magrathea2\DB\QueryUpdate;
use Magrathea2\Exceptions\MagratheaApiException;
use MagratheaCloud\Apikey\ApikeyControl;

class CrawlControl extends \MagratheaCloud\Crawl\Base\CrawlControlBase {

	private function GetKeyIdByValue(string $key) {
		$keyService = new ApikeyControl();
		$apiKey = $keyService->GetByKey($key);
		if(!$apiKey) {
			throw new MagratheaApiException("key [".$key."] does not exists", true, 500);
		}
		return $apiKey;
	}

	public function GetCrawlsByApi(string $key) {
		$apiKey = $this->GetKeyIdByValue($key);
		$query = Query::Select()
			->Obj(new Crawl())
			->Where([ "apikey" => $apiKey->id ]);
		$rs = $this->Run($query);
		$rs = array_map(function($k) { 
			return $k->ToArray();
		}, $rs);
		return $rs;
	}

	public function Create(string $key) {
		$apiKey = $this->GetKeyIdByValue($key);
		$crawl = new Crawl();
		$crawl->status = EnumCrawlStatus::WAITING->value;
		$crawl->apikey = $apiKey->id;
		$crawl->result = " ";
		return $crawl->Insert();
	}

	public function AppendResult($message, $id) {
		// UPDATE test SET image = CONCAT('https://my-site.com/images/',image) WHERE image IS NOT NULL;
		$append = Query::Clean($message)."\n";
		$query = new QueryUpdate();
		$query
			->Table("crawls")
			->SetRaw("result = CONCAT('".$append."', result)")
			->Where([ "id" => $id ]);
		echo $query."<br/>";
		return $this->Run($query);
	}

	public function CheckIfFolderExists($key, $location, $name) {
		return $this->RunExistsQuery("folders", $key, $location, $name);
	}

	public function CheckIfFileExists($key, $location, $name) {
		return $this->RunExistsQuery("files", $key, $location, $name);
	}

	public function RunExistsQuery($table, $key, $location, $name): bool {
		$query = Query::Select("count(1)")
			->Table($table)
			->Where([
				"apikey_id" => $key,
				"location" => $location,
				"name" => $name,
			]);
		$rs = $this->QueryOne($query);
		return $rs > 0;
	}


}
