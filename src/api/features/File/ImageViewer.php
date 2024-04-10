<?php

namespace MagratheaImages3\Images;

use GdImage;
use Magrathea2\Exceptions\MagratheaApiException;
use MagratheaCloud\File\File;

class ImageViewer {

	public File $image;
	public string $file;
	public bool $fileExists = false;
	public bool $saveImage = false;
	public bool $debugOn = false;

	public function __construct(File $img) {
		$this->image = $img;
	}

	public function Debug(): ImageViewer {
		$this->debugOn = true;
		return $this;
	}
	public function ForceGeneration(): bool {
		return (@$_GET["generate"] == "true");
	}

	public function SetFile($filename) {
		$this->file = $filename;
		return $this;
	}

	public static function HeaderExtension(string $extension): bool {
		$ctH = "Content-Type: ";
		switch($extension) {
			case "jpg":
			case "jpeg":
				header($ctH.image_type_to_mime_type(IMAGETYPE_JPEG));
				return true;
			case "png":
				header($ctH.image_type_to_mime_type(IMAGETYPE_PNG));
				return true;
			case "bmp":
				header($ctH.image_type_to_mime_type(IMAGETYPE_BMP));
				return true;
			case "gif":
				header($ctH.image_type_to_mime_type(IMAGETYPE_GIF));
				return true;
			case "webp":
				header($ctH.image_type_to_mime_type(IMAGETYPE_WEBP));
				return true;
			case "wbmp":
				header($ctH.image_type_to_mime_type(IMAGETYPE_WBMP));
				return true;
			case "ico":
				header($ctH.image_type_to_mime_type(IMAGETYPE_ICO));
				return true;
			case "svg":
				header($ctH."image/svg+xml");
				return true;
		}
		return false;
	}

	public static function ViewFromAbsolute($path) {
		$pieces = explode('.', $path);
		$extension = end($pieces);
		$isImage = self::HeaderExtension($extension);
		if($isImage) {
			header("Content-Length: " . filesize($path));
			echo file_get_contents($path);
			// $fp = fopen($path, 'rb');
			// fpassthru($fp);
		} else return false;
	}

	public function ViewFile() {
		self::HeaderExtension($this->image->extension);
		header("Content-Length: ".filesize($this->file));
		// dump the picture and stop the script
		$fp = fopen($this->file, 'rb');
		fpassthru($fp);
		exit;
	}

	public function Raw() {
		return $this->SetFile($this->image->GetFileLocation())->ViewFile();
	}

}
