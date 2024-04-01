<?php

namespace MagratheaCloud;

use Exception;
use Magrathea2\MagratheaHelper;

class Explorer {

	public string $mediaFolder;
	public string $baseFolder = "";
	public string $currentFolder;

	public function __construct(){
		$this->Initialize();
	}

	public function Initialize() {
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$this->mediaFolder = MagratheaHelper::EnsureTrailingSlash($mediaPath);
		return $this;
	}

	public function GetBaseFolder() {
		return MagratheaHelper::EnsureTrailingSlash($this->mediaFolder.$this->baseFolder);
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
