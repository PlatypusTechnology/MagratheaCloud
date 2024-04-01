<?php
namespace MagratheaCloud\Apikey;

use Magrathea2\Exceptions\MagratheaApiException;

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
