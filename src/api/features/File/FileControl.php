<?php
namespace MagratheaCloud\File;

use Magrathea2\DB\Query;
use Magrathea2\Exceptions\MagratheaException;
use Magrathea2\MagratheaHelper;
use MagratheaCloud\Apikey\Apikey;
use MagratheaCloud\Folder\Folder;

class FileControl extends \MagratheaCloud\File\Base\FileControlBase {
	public function GetAllFromKey($keyId) {
		$query = Query::Select()
			->Obj(new File())
			->Where([
				"apikey_id" => $keyId
			]);
		return $this->Run($query);
	}

	public function GetFiles($keyId, $parent) {
		$parent = MagratheaHelper::EnsureTrailingSlash($parent);
		$q = Query::Select()
			->Obj(new File())
			->Where([
				"apikey_id" => $keyId,
				"location" => $parent
			]);
		return $this->Run($q);
	}

	private function GetFolderLocation($folderId) {
		if(!$folderId) {
			return "/";
		} else {
			$folder = new Folder($folderId);
			return $folder->GetLocation();
		}
	}

	public function Upload(Apikey $key, $fileData, $folderId=null) {
		$uploader = new Uploader();
		$uploader->mediaFolder = $key->media_folder;
		$fileName = $fileData["name"];

		try {
			$file = new File();
			$file->keyFolder = $key->media_folder;
			$file->SetUploadFileName($fileName);
			$file->apikey_id = $key->id;
			$file->location_id = intval($folderId);
			$file->location = $this->GetFolderLocation($folderId);

			$uploader->SetFile($file);
			$rs = $uploader->Upload($fileData);
			if(!$rs) {
				$ex = new MagratheaException($rs['error']);
				$ex->SetData($rs['data']);
				throw $ex;
			}
			$file->GetFileData();
			$file->Insert();
			return $file;
		} catch(\Exception $ex) {
			throw new MagratheaException($ex->getMessage());
		}

	}

	public function AddDownload($fileId) {
		/** @var \Magrathea2\DB\QueryUpdate $q */
		$q = Query::Update()->Table("files");
		$q->SetRaw("downloads = downloads + 1")
			->WhereId($fileId);
		return $this->Run($q);
	}

	public function Download(File $file) {
		$this->AddDownload($file->id);
		$downloader = new Downloader();
		return $downloader->DownloadFile($file);
	}

}
