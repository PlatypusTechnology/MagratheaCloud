<?php
namespace MagratheaCloud\Folder;

use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use MagratheaCloud\ApiHelper;

class FolderApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Folder());
		$this->service = new FolderControl();
	}

	public function GetAllFromKey($params) {
		$apikey = ApiHelper::GetApiKey($params);
		return $this->service->GetAllFromKey($apikey->id);
	}

	public function CreateFolder($params) {
		$post = $this->GetPost();
		$apikey = ApiHelper::GetApiKey(($params));
		$locationId = @$post["location"];
		$name = @$post["name"];
		if(!$name) {
			throw new MagratheaApiException("folder name cannot be empty");
		}
		return $this->service->CreateFolder($apikey, $name, $locationId);
	}
}
