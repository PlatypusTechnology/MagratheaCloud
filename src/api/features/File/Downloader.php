<?php
namespace MagratheaCloud\File;

use MagratheaCloud\File\File;

class Downloader {

	public function DownloadFile(File $file) {
		$path = $file->GetFileLocation();
		$this->BuildHeader();
		return $this->Read($path);
	}

	public function BuildHeader() {
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary");
	}

	public function Read(string $path) {
		header("Content-disposition: attachment; filename=\"" . basename($path) . "\"");
		readfile($path);
	}

}
