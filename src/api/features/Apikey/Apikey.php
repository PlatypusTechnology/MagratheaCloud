<?php
namespace MagratheaCloud\Apikey;

use Magrathea2\Exceptions\MagratheaModelException;

class Apikey extends \MagratheaCloud\Apikey\Base\ApikeyBase {

	public function __construct($id=0){
		parent::__construct($id);
	}

	public function SetKey($key) {
		if(!empty($this->val)) {
			$ex = new MagratheaModelException("Key was already defined [".$this->val."]");
			$ex->SetData($this);
			throw $ex;
		}
		$this->val = $key;
	}

	// model code goes here!
}
