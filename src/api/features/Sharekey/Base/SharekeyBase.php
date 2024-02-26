<?php
## FILE GENERATED BY MAGRATHEA.
## This file was automatically generated and changes can be overwritten through the admin
## -- date of creation: [2024-02-24 14:48:17]

namespace MagratheaCloud\Sharekey\Base;

use Magrathea2\iMagratheaModel;
use Magrathea2\MagratheaModel;

class SharekeyBase extends MagratheaModel implements iMagratheaModel {

	public $id, $value, $active;
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
		$this->dbTable = "sharekeys";
		$this->dbPk = "id";
		$this->dbValues["id"] = "int";
		$this->dbValues["value"] = "string";
		$this->dbValues["active"] = "boolean";
		$this->dbValues["created_at"] =  "datetime";
		$this->dbValues["updated_at"] =  "datetime";


	}

	public function GetControl() {
		return new \MagratheaCloud\Sharekey\Base\SharekeyControlBase();
	}

	// >>> relations:

}
