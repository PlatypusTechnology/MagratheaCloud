<?php
namespace MagratheaCloud\Apikey;

class ApikeyApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Apikey());
		$this->service = new ApikeyControl();
	}

	public function GetAll() {
		return $this->List();
	}

}
