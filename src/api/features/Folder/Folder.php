<?php
namespace MagratheaCloud\Folder;

use Magrathea2\MagratheaHelper;
use MagratheaCloud\Apikey\Apikey;

class Folder extends \MagratheaCloud\Folder\Base\FolderBase {

	public function __construct($id=0){
		parent::__construct($id);
	}

	public function GetFolder(string $mediaFolder=null) {
		if (!$mediaFolder) {
			$apiKey = new Apikey($this->apikey_id);
			$mediaFolder = $apiKey->media_folder;
		}
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$path = MagratheaHelper::EnsureTrailingSlash($mediaPath)
			.$mediaFolder.$this->location;

		$path = MagratheaHelper::EnsureTrailingSlash(realpath($path));
		return $path.$this->foldername;
	}

	public function GetLocation() {
		return $this->location.
			MagratheaHelper::EnsureTrailingSlash($this->name);
	}

}
