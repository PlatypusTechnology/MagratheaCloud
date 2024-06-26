<?php
## FILE GENERATED BY MAGRATHEA.
## This file was automatically generated and changes can be overwritten through the admin
## -- date of creation: [2024-04-01 14:01:18]

namespace MagratheaCloud\Crawl\Base;

use Magrathea2\iMagratheaModel;
use Magrathea2\MagratheaModel;

class CrawlBase extends MagratheaModel implements iMagratheaModel {

	public $id, $apikey, $status, $result, $executed_at;
	public $created_at, $updated_at;
	protected $autoload = null;

	public function __construct(  $id=0  ){ 
		$this->MagratheaStart();
		if( !empty($id) ){
			$pk = $this->dbPk;
			$this->$pk = $id;
			$this->GetById($id);
		}
	}
	public function MagratheaStart(){
		$this->dbTable = "crawls";
		$this->dbPk = "id";
		$this->dbValues["id"] = "int";
		$this->dbValues["apikey"] = "int";
		$this->dbValues["status"] = "string";
		$this->dbValues["result"] = "text";
		$this->dbValues["executed_at"] = "datetime";
		$this->dbValues["created_at"] =  "datetime";
		$this->dbValues["updated_at"] =  "datetime";


	}

	public function GetControl() {
		return new \MagratheaCloud\Crawl\Base\CrawlControlBase();
	}

	// >>> relations:

}
