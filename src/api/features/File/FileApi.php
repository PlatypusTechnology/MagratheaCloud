<?php
namespace MagratheaCloud\File;

class FileApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new File());
		$this->service = new FileControl();
	}

}
