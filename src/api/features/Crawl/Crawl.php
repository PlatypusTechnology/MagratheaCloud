<?php
namespace MagratheaCloud\Crawl;

use MagratheaCloud\Apikey\Apikey;
use MagratheaCloud\Apikey\ApikeyControl;

class Crawl extends \MagratheaCloud\Crawl\Base\CrawlBase {

	public Apikey|null $apikeyObject = null;

	public function __construct($id=0){
		parent::__construct($id);
	}

	public function IsWaiting() {
		return $this->status == EnumCrawlStatus::WAITING->value;
	}

	private function GetApiKey(string $key=null) {
		if($this->apikeyObject) return $this->apikeyObject;
		if($key == null) return new Apikey($this->apikey);
		$keyControl = new ApikeyControl();
		$this->apikeyObject = $keyControl->GetByKey($key);
		return $this->apikeyObject;
	}

	public function ValidateKey(string $key): bool {
		if(!$this->GetApiKey($key)) return false;
		return ($this->apikeyObject->id == $this->apikey);
	}

	public function GetFolderKey() {
		return $this->GetApiKey()->media_folder;
	}

	public function RemoveResult() {
		$this->result = null;
	}

	public function ToArray() {
		$rs = parent::ToArray();
		unset($rs["result"]);
		return $rs;
	}

}
