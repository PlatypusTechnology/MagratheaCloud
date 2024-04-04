<?php

namespace MagratheaCloud;

use Exception;
use Magrathea2\Exceptions\MagratheaException;
use Magrathea2\MagratheaHelper;

class Explorer {

	public string $mediaFolder;
	public string $crawlFolder = "";
	public string $currentFolder;

	public function __construct(){
		$this->Initialize();
	}

	public function SetCrawlFolder(string $folder) {
		return $this->crawlFolder = $folder;
	}

	public function Initialize() {
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$this->mediaFolder = MagratheaHelper::EnsureTrailingSlash($mediaPath);
		return $this;
	}

	public function GetBaseFolder() {
		return MagratheaHelper::EnsureTrailingSlash($this->mediaFolder.$this->crawlFolder);
	}

	public function GetFolderData(string $folder) {
		$f = $this->GetFullPathFor($folder);
		$f = realpath($f);
		$this->currentFolder = $f;
		$rs = [];
		if(!file_exists($f)) {
			throw new MagratheaException("folder does not exists: [".$f."] ");
		}
		$data = scandir($f);
		foreach($data as $item) {
			if($item == "." || $item == "..") continue;
			if(is_dir($f."/".$item)) {
				array_push($rs, [
					"item" => $item,
					"type" => "folder",
				]);
			} else {
				array_push($rs, [
					"item" => $item,
					"type" => "file",
				]);
			}
		}
		return $rs;
	}

	public function GetFullPathFor(string $item): string {
		return $this->GetBaseFolder().$item;
	}

	/**
	 * Create Folder
	 * @param 	string 	$folder			folder to be created
	 * @return 	array		[ "success", "folder" ]
	 */
	public function CreateFolder(string $folder): array {
		$f = $this->GetBaseFolder().$folder;
		if(file_exists($f)) {
			$success = true;
		} else {
			$success = @mkdir($f);
		}
		$rs = [
			"success" => $success,
			"folder" => $f
		];
		if(!$success) {
			$error = error_get_last();
			$rs["error"] = $error["message"];
		}
		return $rs;
	}

}
