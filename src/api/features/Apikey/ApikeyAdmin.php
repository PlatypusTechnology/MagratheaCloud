<?php
namespace MagratheaCloud\Apikey;

class ApikeyAdmin extends \Magrathea2\Admin\Features\CrudObject\AdminCrudObject {
	public string $featureName = "Apikey CRUD";

	public function Initialize() {
		$this->SetObject(new Apikey());
	}
}
