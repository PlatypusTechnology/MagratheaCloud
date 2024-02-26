<?php
namespace MagratheaCloud\File;

class FileAdmin extends \Magrathea2\Admin\Features\CrudObject\AdminCrudObject {
	public string $featureName = "File CRUD";

	public function Initialize() {
		$this->SetObject(new File());
	}
}
