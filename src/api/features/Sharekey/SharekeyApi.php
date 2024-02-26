<?php
namespace MagratheaCloud\Sharekey;

class SharekeyApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Sharekey());
		$this->service = new SharekeyControl();
	}

}
