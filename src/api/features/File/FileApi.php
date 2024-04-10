<?php
namespace MagratheaCloud\File;

use Downloader;
use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use MagratheaCloud\ApiHelper;
use MagratheaImages3\Images\ImageViewer;

class FileApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new File());
		$this->service = new FileControl();
	}

	public function _GetFile($params) {
		$apikey = ApiHelper::GetApiKey($params);
		$fileId = @$params["file_id"];
		$file = new File($fileId);
		if(!$file->IsEmpty()) {
			throw new MagratheaApiException("file does not exists", 404, $params);
		}
		if($file->apikey_id != $apikey->id) {
			throw new MagratheaApiException("file does not belong to api key", 401, $params);
		}
		$file->keyFolder = $apikey->media_folder;
		return $file;
	}

	public function GetAllFromKey($params) {
		$apikey = ApiHelper::GetApiKey($params);
		return $this->service->GetAllFromKey($apikey->id);
	}

	public function GetFile($params) {
		return $this->_GetFile($params);
	}

	public function GetAsImage($params) {
		$file = $this->_GetFile($params);
		if(!$file->IsImage()) {
			throw new MagratheaApiException("file is not a image", 500, $file);
		}
		$imageViewer = new ImageViewer($file);
		return $imageViewer->Raw();
	}

	private function HandleFiles($f, $index) {
		$file = [
			"error" => $f["error"][$index],
			"name" => $f["name"][$index],
			"full_path" => $f["full_path"][$index],
			"size" => $f["size"][$index],
			"tmp_name" => $f["tmp_name"][$index],
			"type" => $f["type"][$index],
		];
		return $file;
	}

	public function Download($params) {
		$file = $this->_GetFile($params);
		$this->service->Download($file);
	}
	public function Upload($params) {
		$post = $this->GetPost();
		$apikey = ApiHelper::GetApiKey($params);
		$parent = @$post["folder"];
		$fileKey = "files";
		try {
			if(!$_FILES || count($_FILES[$fileKey]["tmp_name"]) == 0) {
				throw new MagratheaApiException("Files multipart is empty");
			}
			$files = $this->HandleFiles($_FILES[$fileKey], 0);
		} catch(\Exception $ex) {
			throw new MagratheaApiException($ex->getMessage(), 500, $files);
		}
		return $this->service->Upload($apikey, $files, $parent);
	}

}
