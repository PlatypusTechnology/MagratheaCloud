<?php
namespace MagratheaCloud\File;

use Magrathea2\MagratheaHelper;
use MagratheaCloud\CloudHelper;

class File extends \MagratheaCloud\File\Base\FileBase {

	public string $keyFolder;

	public function __construct($id=0){
		parent::__construct($id);
	}

	public function SetFile($filename): File {
		$this->filename = $filename;
		$this->name = pathinfo($filename, PATHINFO_FILENAME);
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$this->extension = strtolower($ext);
		return $this;
	}
	public function SetUploadFileName($filename): File {
		$nextId = $this->GetNextID();
		$this->SetFile($filename);
		$uploadFile = $nextId."_".$filename;
		$this->filename = $uploadFile;
		return $this;
	}

	public function GetFileData(): File {
		$path = $this->GetFileLocation();
		$size = filesize($path);
		$this->type = CloudHelper::GetFileType($this->extension);
		$this->size = $size;
		$this->sent_at = date ("Y-m-d H:i:s", filemtime($path));
		$this->mime = mime_content_type($path);
		return $this;
	}

	public function GetMime(): string {
		$path = $this->GetFileLocation();
		return mime_content_type($path);
	}

	public function GetFileLocation(): string {
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$file = 
			MagratheaHelper::EnsureTrailingSlash($mediaPath).
			MagratheaHelper::EnsureTrailingSlash($this->keyFolder).
			MagratheaHelper::EnsureTrailingSlash($this->location).
			$this->filename;
		return realpath($file);
	}

	public function IsImage(): bool {
		return CloudHelper::GetFileType($this->extension === "image");
	}

}
