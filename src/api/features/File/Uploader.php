<?php
namespace MagratheaCloud\File;

use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\Exceptions\MagratheaException;
use Magrathea2\Helper;
use Magrathea2\Logger;
use Magrathea2\MagratheaHelper;
use MagratheaCloud\File\File;

class Uploader {

	public File|null $file;
	public string $mediaFolder;

	public function GetDestination(): string {
		$mediaPath = \Magrathea2\ConfigApp::Instance()->Get("medias_path");
		$filePath = $this->file->location;
		$path = MagratheaHelper::EnsureTrailingSlash($mediaPath)
			.$this->mediaFolder
			.MagratheaHelper::EnsureTrailingSlash($filePath);
		return $path;
	}
	public static function CheckDestinationFolder($path): array {
		if (is_dir($path)) return [ "success" => true, "path" => $path ];
		try {
			if(mkdir($path, 0755, true)) {
				return [ "success" => true, "path" => $path ];
			} else {
				return [ "success" => false, "error" => "unknown error", "path" => $path ];
			}
			if(!is_writeable($path)){
				return [ "success" => false, "error" => "destination path has no writing permission", "path" => $path ];
			}
		} catch(\Exception $e) {
			Logger::Instance()->Log("error creating upload folder ". $e->getMessage());
			return [ "success" => false, "error" => $e->getMessage(), "path" => $path ];
		}
	}

	public function ValidateDestination(string $path): bool {
		$destinationOk = $this->CheckDestinationFolder($path);
		if(!$destinationOk["success"]) {
			throw new MagratheaApiException($destinationOk["error"], 500, $destinationOk["path"]);
		}
		return true;
	}

	public function SetFile(File $file): Uploader {
		if(empty($file)) {
			throw new MagratheaException("Empty file");
		}
		$this->file = $file;
		return $this;
	}

	public function returnSuccess(): array {
		return [
			"success" => true,
			"file" => $this->file,
		];
	}
	public function returnFileNotUploaded(): array {
		return [
			"success" => false,
			"error" => "file was not uploaded",
			"data" => $this->file,
		];
	}

	public function Upload($file): array {
		try {
			$path = $this->GetDestination();
			$this->ValidateDestination($path);
			$finalName = MagratheaHelper::EnsureTrailingSlash($path)
				.$this->file->filename;
			move_uploaded_file($file["tmp_name"], $finalName);
			if(file_exists($finalName)){
				$this->file->Insert();
			} else {
				return $this->returnFileNotUploaded();
			}
			return $this->returnSuccess();
		} catch(MagratheaApiException $ex) {
			return [
				"success" => false,
				"error" => $ex->getMessage(),
				"data" => $ex->GetData(),
			];
		} catch(\Exception $e) {
			return [
				"success" => false,
				"error"=> $e->getMessage()
			];
		}
	}

}
