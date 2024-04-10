<?php
namespace MagratheaCloud\Apikey;

use Magrathea2\DB\Database;
use Magrathea2\DB\Query;
use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\Exceptions\MagratheaException;
use Magrathea2\Exceptions\MagratheaModelException;
use Magrathea2\MagratheaHelper;
use MagratheaCloud\CloudHelper;
use MagratheaCloud\Explorer;
use MagratheaCloud\File\File;
use MagratheaCloud\Folder\Folder;

class ApikeyControl extends \MagratheaCloud\Apikey\Base\ApikeyControlBase {

	public function Create(string $name) {
		$folder = CloudHelper::cleanString($name);
		try {
			$this->CreateBaseFolder($folder);
			$apiKey = new Apikey();
			$apiKey->media_folder = $folder;
			$apiKey->active = true;
			$apiKey->setKey($this->CreateKey());
			$apiKey->Insert();
			return $apiKey;
		} catch(\Exception $ex) {
			throw $ex;
		}
	}

	public function CreateBaseFolder($folder): bool {
		$explorer = new \MagratheaCloud\Explorer();
		$createRs = $explorer->CreateFolder($folder);
		if($createRs["success"]) {
			return true;
		}
		$ex = new MagratheaException("Create base folder failed");
		$ex->SetData($createRs);
		throw $ex;
	}

	public function CreateKey($tries=0): string {
		$length = rand(20, 30);
		$key = MagratheaHelper::RandomString($length);
		if(!$this->AssertKeyNotInUse($key)) {
			$tries = $tries + 1;
			if($tries > 5) throw new MagratheaModelException("incorrect key creation (after ".$tries." tries)");
			return $this->CreateKey($tries);
		}
		return $key;
	}

	public function AssertKeyNotInUse($key): bool {
		$q = Query::Select("COUNT(1) as ok")
			->Table("apikey")
			->Where(["val" => $key]);
		$rs = Database::Instance()->QueryOne($q);
		return ($rs == 0);
	}

	public function GetByKey($key): Apikey|null {
		$q = Query::Select()
			->Obj(new Apikey())
			->Where(["val" => $key]);
		return $this->RunRow($q);
	}

}
