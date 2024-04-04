<?php
namespace MagratheaCloud\File;

use Magrathea2\MagratheaHelper;
use MagratheaCloud\CloudHelper;

class File extends \MagratheaCloud\File\Base\FileBase {

	public string $keyFolder;

	public function __construct($id=0){
		parent::__construct($id);
	}

	public function GetFileData(): File {
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$file = 
			MagratheaHelper::EnsureTrailingSlash($mediaPath).
			MagratheaHelper::EnsureTrailingSlash($this->keyFolder).
			MagratheaHelper::EnsureTrailingSlash($this->location).
			$this->name;
		$path = realpath($file);
		$size = filesize($path);
		$ext = pathinfo($this->name, PATHINFO_EXTENSION);
		$this->extension = strtolower($ext);
		$this->type = CloudHelper::GetFileType($this->extension);
		$this->size = $size;
		$this->sent_at = date ("Y-m-d H:i:s", filemtime($file));
		return $this;
	}
	// model code goes here!
}
