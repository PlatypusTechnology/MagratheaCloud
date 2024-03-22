<?php
namespace MagratheaCloud\Folder;

class FolderAdmin extends \Magrathea2\Admin\Features\CrudObject\AdminCrudObject {
	public string $featureName = "Folder CRUD";

	public function Initialize() {
		$this->SetObject(new Folder());
	}
}
