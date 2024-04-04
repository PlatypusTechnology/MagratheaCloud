<?php
namespace MagratheaCloud\Crawl;

class CrawlAdmin extends \Magrathea2\Admin\Features\CrudObject\AdminCrudObject {
	public string $featureName = "Crawl CRUD";

	public function Initialize() {
		$this->SetObject(new Crawl());
	}
}
