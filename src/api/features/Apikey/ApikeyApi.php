<?php
namespace MagratheaCloud\Apikey;

use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use MagratheaCloud\File\FileControl;
use MagratheaCloud\Folder\Folder;
use MagratheaCloud\Folder\FolderControl;

class ApikeyApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Apikey());
		$this->service = new ApikeyControl();
	}

	public function GetAll() {
		return $this->List();
	}

	public function Create() {
		$data = $this->GetPost();
		$name = @$data["media_folder"] ? @$data["media_folder"] : @$data["name"];
		if(empty($name)) {
			throw new MagratheaApiException("Folder name is empty ('media_folder') ", 500, $data);
		}
		$control = new ApikeyControl();
		$api = $control->Create($name);
		return $api;
	}

	private function ExploreFolders($keyId, $parent) {
		$folderControl = new FolderControl();
		$folders = $folderControl->GetFolders($keyId, $parent);
		return array_map(function($f) {
			return [
				"id" => $f->id,
				"type" => "folder",
				"name" => $f->name,
				"size" => null,
				"created" => $f->created_at,
			];
		}, $folders);
	}
	private function ExploreFiles($keyId, $parent) {
		$fileControl = new FileControl();
		$files = $fileControl->GetFiles($keyId, $parent);
		return array_map(function($f) {
			return [
				"id" => $f->id,
				"type" => $f->type,
				"name" => $f->name.".".$f->extension,
				"size" => $f->size,
				"created" => $f->sent_at,
			];
		}, $files);
	}
	public function Explore($params) {
		$key = $this->_GetKey($params);
		$folderId = @$_GET["folder"];
		$parent = "/";
		if($folderId) {
			$folder = new Folder($folderId);
			if($folder->apikey_id !== $key->id) {
				throw new MagratheaApiException("Folder does not belong to key");
			}
			$parent = $folder->location.$folder->name;
		}
		return [
			"parent_id" => $folderId,
			"location" => $parent,
			"content" => [
				...$this->ExploreFolders($key->id, $parent),
				...$this->ExploreFiles($key->id, $parent),
			],
		];
	}

	private function _GetKey($params): Apikey {
		$val = $params["key"];
		if(empty($val)) {
			throw new MagratheaApiException("key is empty", true, 404, $val);
		}
		$key = $this->service->GetByKey($val);
		if(empty($key)) {
			throw new MagratheaApiException("key [".$val."] does not exists", true, 404, $val);
		}
		return $key;
	}

	public function GetByKey($params) {
		return $this->_GetKey($params);
	}

}
