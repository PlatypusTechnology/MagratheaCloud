<?php
namespace MagratheaCloud\Folder;

use Magrathea2\DB\Query;
use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaHelper;
use MagratheaCloud\Apikey\Apikey;
use MagratheaCloud\CloudHelper;

class FolderControl extends \MagratheaCloud\Folder\Base\FolderControlBase {
	public function GetAllFromKey($keyId) {
		$query = Query::Select()
			->Obj(new Folder())
			->Where([
				"apikey_id" => $keyId
			]);
		return $this->Run($query);
	}

	public function GetFolders($keyId, $parent) {
		$parent = MagratheaHelper::EnsureTrailingSlash($parent);
		$q = Query::Select()
			->Obj(new Folder())
			->Where([
				"apikey_id" => $keyId,
				"location" => $parent
			]);
		return $this->Run($q);
	}

	public function GetFolderIfExists($key, $locationId, $name) {
		$name = Query::Clean($name);
		$q = Query::Select()
		->Obj(new Folder())
		->Where([
			"apikey_id" => $key,
			"location_id" => $locationId,
			"name" => $name,
		]);
		return $this->RunRow($q);
	}

	public function CreateFolder(Apikey $key, string $folderName, int|null $locationId=null) {
		$folder = new Folder();
		if(!$locationId) {
			$folder->location_id = null;
			$folder->location = "/";
		} else {
			$parent = new Folder($locationId);
			if($parent->apikey_id != $key->id) {
				throw new MagratheaApiException("can't create folder in this location!", 500);
			}
			$folder->location_id = $locationId;
			$folder->location = 
				MagratheaHelper::EnsureTrailingSlash($parent->location).
				MagratheaHelper::EnsureTrailingSlash($parent->foldername);
		}
		$folder->apikey_id = $key->id;
		$folder->name = $folderName;
		$folder->foldername = CloudHelper::cleanString($folderName);
		try {
			$path = $folder->GetFolder($key->media_folder);
			$this->MkdirFolder($path);
		} catch(MagratheaApiException $err) {
			throw $err;
		}
		$folder->Insert();
		return $folder;
	}

	private function MkdirFolder($path) {
		try {
			$created = FolderCreator::Create($path);
			if(!$created) {
				throw new MagratheaApiException("could not create folder ".$path);
			}
			return $created;
		} catch(MagratheaApiException $err) {
			throw $err;
		} catch(\Exception $ex) {
			throw new MagratheaApiException($ex->getMessage());
		}

	}
}
