<?php
namespace MagratheaCloud\Folder;

class FolderApi extends \Magrathea2\MagratheaApiControl {
	public function __construct() {
		$this->model = get_class(new Folder());
		$this->service = new FolderControl();
	}

}
