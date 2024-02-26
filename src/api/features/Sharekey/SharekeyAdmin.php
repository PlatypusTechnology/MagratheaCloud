<?php
namespace MagratheaCloud\Sharekey;

class SharekeyAdmin extends \Magrathea2\Admin\Features\CrudObject\AdminCrudObject {
	public string $featureName = "Sharekey CRUD";

	public function Initialize() {
		$this->SetObject(new Sharekey());
	}
}
